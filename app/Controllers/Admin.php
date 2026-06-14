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
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');
        
        $totalExams = $this->examModel->countAll();
        $totalUsers = $this->userModel->where('role', 'user')->countAllResults();
        $totalQuestions = $this->questionModel->countAll();
        
        $users = $this->userModel
            ->select('users.*, 
                      COUNT(DISTINCT exam_registrations.exam_id) as total_exams_taken,
                      COUNT(DISTINCT exam_sessions.id) as total_sessions,
                      MAX(exam_sessions.start_time) as last_activity')
            ->join('exam_registrations', 'exam_registrations.user_id = users.id', 'left')
            ->join('exam_sessions', 'exam_sessions.user_id = users.id', 'left')
            ->where('users.role', 'user')
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();
        
        $examSessions = $this->examSessionModel
            ->select('exam_sessions.*, users.name as user_name, users.email as user_email, exams.title as exam_title')
            ->join('users', 'users.id = exam_sessions.user_id')
            ->join('exams', 'exams.id = exam_sessions.exam_id')
            ->where('exam_sessions.start_time >=', $startDate . ' 00:00:00')
            ->where('exam_sessions.start_time <=', $endDate . ' 23:59:59')
            ->orderBy('exam_sessions.start_time', 'DESC')
            ->findAll();
        
        $periodStats = [
            'total_sessions' => count($examSessions),
            'completed' => count(array_filter($examSessions, fn($s) => $s['status'] === 'finished')),
            'ongoing' => count(array_filter($examSessions, fn($s) => $s['status'] === 'ongoing')),
            'expired' => count(array_filter($examSessions, fn($s) => $s['status'] === 'expired')),
        ];
        
        $data = [
            'title' => 'Admin Dashboard',
            'totalExams' => $totalExams,
            'totalUsers' => $totalUsers,
            'totalQuestions' => $totalQuestions,
            'users' => $users,
            'examSessions' => $examSessions,
            'periodStats' => $periodStats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function getDashboardData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $startDate = $this->request->getPost('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getPost('end_date') ?: date('Y-m-d');
        $this->examSessionModel
        ->where('status', 'ongoing')
        ->where('end_time <', date('Y-m-d H:i:s')) 
        ->set('status', 'expired')
        ->update();

        $totalExams = $this->examModel->countAll();
        $totalUsers = $this->userModel->where('role', 'user')->countAllResults();
        $totalQuestions = $this->questionModel->countAll();
        
        $users = $this->userModel
            ->select('users.*, 
                      COUNT(DISTINCT exam_registrations.exam_id) as total_exams_taken,
                      COUNT(DISTINCT exam_sessions.id) as total_sessions,
                      MAX(exam_sessions.start_time) as last_activity')
            ->join('exam_registrations', 'exam_registrations.user_id = users.id', 'left')
            ->join('exam_sessions', 'exam_sessions.user_id = users.id', 'left')
            ->where('users.role', 'user')
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();
        
        $examSessions = $this->examSessionModel
            ->select('exam_sessions.*, users.name as user_name, users.email as user_email, exams.title as exam_title')
            ->join('users', 'users.id = exam_sessions.user_id')
            ->join('exams', 'exams.id = exam_sessions.exam_id')
            ->where('exam_sessions.start_time >=', $startDate . ' 00:00:00')
            ->where('exam_sessions.start_time <=', $endDate . ' 23:59:59')
            ->orderBy('exam_sessions.start_time', 'DESC')
            ->findAll();
        
        $periodStats = [
            'total_sessions' => count($examSessions),
            'completed' => count(array_filter($examSessions, fn($s) => $s['status'] === 'finished')),
            'ongoing' => count(array_filter($examSessions, fn($s) => $s['status'] === 'ongoing')),
            'expired' => count(array_filter($examSessions, fn($s) => $s['status'] === 'expired')),
        ];
        
        $formattedSessions = [];
        foreach ($examSessions as $session) {
            $statusClass = 'secondary';
            if ($session['status'] === 'finished') $statusClass = 'success';
            elseif ($session['status'] === 'ongoing') $statusClass = 'warning';
            elseif ($session['status'] === 'expired') $statusClass = 'danger';
            
            $formattedSessions[] = [
                'user_name' => $session['user_name'],
                'user_email' => $session['user_email'],
                'exam_title' => $session['exam_title'],
                'start_time' => date('d/m/Y H:i', strtotime($session['start_time'])),
                'end_time' => $session['end_time'] ? date('d/m/Y H:i', strtotime($session['end_time'])) : '-',
                'status' => ucfirst($session['status']),
                'status_class' => $statusClass,
                'duration' => $session['total_time_taken'] ? round($session['total_time_taken'], 2) . ' menit' : '-',
            ];
        }
        
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'total_exams_taken' => $user['total_exams_taken'] ?? 0,
                'total_sessions' => $user['total_sessions'] ?? 0,
                'last_activity' => $user['last_activity'] 
                    ? date('d M Y H:i', strtotime($user['last_activity'])) 
                    : 'Belum ada aktivitas',
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'totalExams' => $totalExams,
                'totalUsers' => $totalUsers,
                'totalQuestions' => $totalQuestions,
            ],
            'periodStats' => $periodStats,
            'examSessions' => $formattedSessions,
            'users' => $formattedUsers,
            'csrf_token' => csrf_hash()
        ]);
    }
    
    public function getExtraTimeData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $ongoingSessions = $this->examSessionModel
            ->select('exam_sessions.*, users.name as user_name, exams.title as exam_title')
            ->join('users', 'users.id = exam_sessions.user_id')
            ->join('exams', 'exams.id = exam_sessions.exam_id')
            ->where('exam_sessions.status', 'ongoing')
            ->findAll();
        
        $formattedSessions = [];
        foreach ($ongoingSessions as $session) {
            $formattedSessions[] = [
                'id' => $session['id'],
                'user_name' => $session['user_name'],
                'exam_title' => $session['exam_title'],
                'start_time' => date('H:i:s', strtotime($session['start_time'])),
                'end_time' => date('H:i:s', strtotime($session['end_time'])),
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'sessions' => $formattedSessions,
            'csrf_token' => csrf_hash()
        ]);
    }
    
    public function exams()
    {
        $exams = $this->examModel->orderBy('id', 'DESC')->findAll();
        
        $data = [
            'title' => 'Manage Exams',
            'exams' => $exams
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
                $status = $this->request->getPost('status') ?? 'draft';
                
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'duration_minutes' => (int)$this->request->getPost('duration_minutes'),
                    'total_questions' => 0,
                    'status' => $status,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                
                $newExamId = $this->examModel->insert($data);
                
                if ($status === 'published') {
                    $this->examModel->update($newExamId, ['status' => 'draft']);
                    return redirect()->to('/admin/exams/' . $newExamId . '/questions/add')
                        ->with('warning', 'Ujian dibuat sebagai Draft. Tambahkan minimal 1 soal sebelum publish.');
                }
                
                return redirect()->to('/admin/exams')->with('success', 'Ujian berhasil dibuat sebagai Draft!');
            }
        }
        
        return view('admin/exams/create', ['title' => 'Create New Exam']);
    }
    
    public function publishExam($examId)
    {
        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Ujian tidak ditemukan');
        }
        
        $questionCount = $this->questionModel->where('exam_id', $examId)->countAllResults();
        
        if ($questionCount < 1) {
            return redirect()->to('/admin/exams/' . $examId . '/questions')
                ->with('error', 'Tidak bisa publish! Tambahkan minimal 1 soal terlebih dahulu.');
        }
        
        $this->examModel->update($examId, ['status' => 'published']);
        
        return redirect()->to('/admin/exams')->with('success', 'Ujian berhasil dipublish!');
    }
    
    public function unpublishExam($examId)
    {
        $this->examModel->update($examId, ['status' => 'draft']);
        return redirect()->to('/admin/exams')->with('success', 'Ujian berhasil diubah ke draft');
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
                    $uploadPath = FCPATH . 'uploads/questions/';
                    
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0775, true);
                    }
                    
                    $validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!in_array($file->getMimeType(), $validTypes)) {
                        return redirect()->back()->with('error', 'Tipe file tidak valid')->withInput();
                    }
                    
                    if ($file->getSizeByUnit('mb') > 2) {
                        return redirect()->back()->with('error', 'Ukuran file maksimal 2MB')->withInput();
                    }
                    
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $imagePath = 'uploads/questions/' . $newName;
                }
                
                $options = [];
                for ($i = 'A'; $i <= 'D'; $i++) {
                    $options[$i] = $this->request->getPost('option_' . $i);
                }
                
                $order = $this->questionModel->where('exam_id', $examId)->countAllResults() + 1;
                
                $questionData = [
                    'exam_id' => $examId,
                    'question_text' => $this->request->getPost('question_text'),
                    'image_path' => $imagePath,
                    'options' => json_encode($options),
                    'correct_answer' => $this->request->getPost('correct_answer'),
                    'order' => $order,
                ];
                
                $this->questionModel->insert($questionData);
                
                $totalQuestions = $this->questionModel->where('exam_id', $examId)->countAllResults();
                $this->examModel->update($examId, ['total_questions' => $totalQuestions]);
                
                $message = 'Soal berhasil ditambahkan!';
                if ($totalQuestions === 1) {
                    $message .= ' Sekarang Anda bisa publish ujian ini.';
                }
                
                return redirect()->to('/admin/exams/' . $examId . '/questions')->with('success', $message);
            }
        }
        
        $questionCount = $this->questionModel->where('exam_id', $examId)->countAllResults();
        
        return view('admin/questions/add', [
            'title' => 'Add Question',
            'exam' => $exam,
            'questionCount' => $questionCount
        ]);
    }
    
    public function addExtraTime()
    {
        if ($this->request->getMethod() === 'POST') {
            $sessionId = $this->request->getPost('session_id');
            $extraMinutes = (int)$this->request->getPost('extra_minutes');
            
            $session = $this->examSessionModel->find($sessionId);
            
            if ($session && $session['status'] === 'ongoing') {
                $newEndTime = date('Y-m-d H:i:s', strtotime($session['end_time'] . ' +' . $extraMinutes . ' minutes'));
                $this->examSessionModel->update($sessionId, ['end_time' => $newEndTime]);
                
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => "Berhasil menambah {$extraMinutes} menit",
                        'new_end_time' => $newEndTime,
                        'csrf_token' => csrf_hash()
                    ]);
                }
                
                return redirect()->back()->with('success', "Berhasil menambah {$extraMinutes} menit");
            }
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sesi tidak ditemukan atau sudah selesai',
                    'csrf_token' => csrf_hash()
                ]);
            }
            
            return redirect()->back()->with('error', 'Sesi tidak ditemukan atau sudah selesai');
        }
        
        $data = [
            'title' => 'Add Extra Time',
        ];
        
        return view('admin/extra_time', $data);
    }
}