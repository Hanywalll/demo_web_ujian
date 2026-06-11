<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\QuestionModel;
use App\Models\ExamSessionModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $examModel;
    protected $questionModel;
    protected $examSessionModel;
    protected $userModel;
    
    public function __construct()
    {
        helper(['form', 'url']);
        $this->examModel = new ExamModel();
        $this->questionModel = new QuestionModel();
        $this->examSessionModel = new ExamSessionModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'totalExams' => $this->examModel->countAll(),
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'totalQuestions' => $this->questionModel->countAll(),
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function exams()
    {
        $data = [
            'title' => 'Manage Exams',
            'exams' => $this->examModel->findAll()
        ];
        
        return view('admin/exams/index', $data);
    }
    
    public function createExam()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]',
                'description' => 'required',
                'duration_minutes' => 'required|integer|greater_than[0]',
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'duration_minutes' => $this->request->getPost('duration_minutes'),
                    'total_questions' => 0,
                    'status' => $this->request->getPost('status') ?? 'draft',
                ];
                
                $this->examModel->insert($data);
                return redirect()->to('/admin/exams')->with('success', 'Exam created successfully');
            }
        }
        
        return view('admin/exams/create', ['title' => 'Create Exam']);
    }
    
    public function questions($examId)
    {
        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }
        
        $data = [
            'title' => 'Manage Questions - ' . $exam['title'],
            'exam' => $exam,
            'questions' => $this->questionModel->where('exam_id', $examId)->orderBy('order', 'ASC')->findAll()
        ];
        
        return view('admin/questions/index', $data);
    }
    
    public function addQuestion($examId)
    {
        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'question_text' => 'required',
                'correct_answer' => 'required',
            ];
            
            if ($this->validate($rules)) {
                $imagePath = null;
                $file = $this->request->getFile('question_image');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/questions', $newName);
                    $imagePath = 'uploads/questions/' . $newName;
                }
                
                $options = [];
                for ($i = 'A'; $i <= 'D'; $i++) {
                    $options[$i] = $this->request->getPost('option_' . $i);
                }
                
                $order = $this->questionModel->where('exam_id', $examId)->countAllResults() + 1;
                
                $data = [
                    'exam_id' => $examId,
                    'question_text' => $this->request->getPost('question_text'),
                    'image_path' => $imagePath,
                    'options' => json_encode($options),
                    'correct_answer' => $this->request->getPost('correct_answer'),
                    'order' => $order,
                ];
                
                $this->questionModel->insert($data);
                
                $this->examModel->update($examId, [
                    'total_questions' => $this->questionModel->where('exam_id', $examId)->countAllResults()
                ]);
                
                return redirect()->to('/admin/exams/' . $examId . '/questions')->with('success', 'Question added successfully');
            }
        }
        
        return view('admin/questions/add', [
            'title' => 'Add Question',
            'exam' => $exam
        ]);
    }
    
    public function addExtraTime()
    {
        if ($this->request->getMethod() === 'POST') {
            $sessionId = $this->request->getPost('session_id');
            $extraMinutes = $this->request->getPost('extra_minutes');
            
            $session = $this->examSessionModel->find($sessionId);
            if ($session && $session['status'] === 'ongoing') {
                $newEndTime = date('Y-m-d H:i:s', strtotime($session['end_time'] . ' +' . $extraMinutes . ' minutes'));
                $this->examSessionModel->update($sessionId, ['end_time' => $newEndTime]);
                
                return $this->response->setJSON(['success' => true, 'message' => 'Extra time added']);
            }
        }
        
        $data = [
            'title' => 'Add Extra Time',
            'ongoingSessions' => $this->examSessionModel
                ->select('exam_sessions.*, users.name as user_name, exams.title as exam_title')
                ->join('users', 'users.id = exam_sessions.user_id')
                ->join('exams', 'exams.id = exam_sessions.exam_id')
                ->where('exam_sessions.status', 'ongoing')
                ->findAll()
        ];
        
        return view('admin/extra_time', $data);
    }
}