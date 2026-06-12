<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\QuestionModel;
use App\Models\ExamRegistrationModel;
use App\Models\ExamSessionModel;
use App\Models\UserAnswerModel;

class Exam extends BaseController
{
    protected $examModel;
    protected $questionModel;
    protected $registrationModel;
    protected $sessionModel;
    protected $answerModel;
    
    public function __construct()
    {
        helper(['form', 'url']);
        $this->examModel = new ExamModel();
        $this->questionModel = new QuestionModel();
        $this->registrationModel = new ExamRegistrationModel();
        $this->sessionModel = new ExamSessionModel();
        $this->answerModel = new UserAnswerModel();
    }
    
    public function index()
    {
        $userId = session()->get('user_id');
        $exams = $this->examModel->where('status', 'published')->findAll();
        
        foreach ($exams as &$exam) {
            $exam['registered'] = $this->registrationModel
                ->where('user_id', $userId)
                ->where('exam_id', $exam['id'])
                ->first() ? true : false;
        }
        
        return view('user/exams', [
            'title' => 'Available Exams',
            'exams' => $exams
        ]);
    }
    
    public function register($examId)
    {
        $userId = session()->get('user_id');
        
        $registration = $this->registrationModel
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->first();
        
        if (!$registration) {
            $this->registrationModel->insert([
                'user_id' => $userId,
                'exam_id' => $examId
            ]);
            
            return redirect()->to('/exam')->with('success', 'Registered successfully');
        }
        
        return redirect()->to('/exam')->with('error', 'Already registered');
    }
    
    public function start($examId)
    {
    $userId = session()->get('user_id');
    $exam = $this->examModel->find($examId);
    
    if (!$exam || $exam['status'] !== 'published') {
        return redirect()->to('/exam')->with('error', 'Ujian tidak tersedia');
    }
    
    // Cek registrasi
    $registration = $this->registrationModel
        ->where('user_id', $userId)
        ->where('exam_id', $examId)
        ->first();
    
    if (!$registration) {
        return redirect()->to('/exam')->with('error', 'Silakan daftar dulu');
    }
    
    // Cek apakah sudah ada sesi yang ongoing
    $session = $this->sessionModel
        ->where('user_id', $userId)
        ->where('exam_id', $examId)
        ->where('status', 'ongoing')
        ->first();
    
    if (!$session) {
        // Hitung waktu mulai dan selesai
        $startTime = date('Y-m-d H:i:s');
        
        // PENTING: Tambahkan durasi dengan BENAR
        $durationMinutes = (int)$exam['duration_minutes'];
        $endTime = date('Y-m-d H:i:s', strtotime("+{$durationMinutes} minutes"));
        
        // Debug: cek nilai
        log_message('debug', 'Start Time: ' . $startTime);
        log_message('debug', 'Duration: ' . $durationMinutes . ' minutes');
        log_message('debug', 'End Time: ' . $endTime);
        
        // Simpan sesi baru
        $sessionData = [
            'user_id' => $userId,
            'exam_id' => $examId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'ongoing'
        ];
        
        $sessionId = $this->sessionModel->insert($sessionData);
        
        // Verifikasi insert berhasil
        if (!$sessionId) {
            return redirect()->to('/exam')->with('error', 'Gagal membuat sesi ujian');
        }
    } else {
        $sessionId = $session['id'];
    }
    
    // Ambil soal pertama
    $firstQuestion = $this->questionModel
        ->where('exam_id', $examId)
        ->orderBy('order', 'ASC')
        ->first();
    
    if (!$firstQuestion) {
        return redirect()->to('/exam')->with('error', 'Belum ada soal dalam ujian ini');
    }
    
    return redirect()->to('/exam/take/' . $sessionId . '/question/' . $firstQuestion['id']);
    }
    
    public function takeExam($sessionId, $questionId = null)
    {
        $session = $this->sessionModel->find($sessionId);
        if (!$session || $session['status'] !== 'ongoing') {
            return redirect()->to('/exam')->with('error', 'Session not available');
        }
        
        if ($session['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/exam')->with('error', 'Unauthorized access');
        }
        
        $exam = $this->examModel->find($session['exam_id']);
        $questions = $this->questionModel
            ->where('exam_id', $session['exam_id'])
            ->orderBy('order', 'ASC')
            ->findAll();
        
        if (empty($questions)) {
            return redirect()->to('/exam')->with('error', 'No questions available');
        }
        
        if (!$questionId) {
            $questionId = $questions[0]['id'];
        }
        
        $currentQuestion = $this->questionModel->find($questionId);
        if (!$currentQuestion) {
            return redirect()->to('/exam/take/' . $sessionId . '/question/' . $questions[0]['id']);
        }
        
        $answers = $this->answerModel
            ->where('session_id', $sessionId)
            ->findAll();
        
        $answersMap = [];
        foreach ($answers as $answer) {
            $answersMap[$answer['question_id']] = $answer['selected_answer'];
        }
        
        $navigation = [];
        foreach ($questions as $q) {
            $status = 'unanswered';
            if (isset($answersMap[$q['id']])) {
                if ($answersMap[$q['id']] === 'doubtful') {
                    $status = 'doubtful';
                } else {
                    $status = 'answered';
                }
            }
            $navigation[] = [
                'id' => $q['id'],
                'order' => $q['order'],
                'status' => $status
            ];
        }
        
        $data = [
            'title' => $exam['title'],
            'session' => $session,
            'exam' => $exam,
            'currentQuestion' => $currentQuestion,
            'questions' => $questions,
            'navigation' => $navigation,
            'answersMap' => $answersMap,
            'csrf_token' => csrf_hash(),
            'csrf_name' => csrf_token()
        ];
        
        return view('user/take_exam', $data);
    }
    
    public function saveAnswer()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $sessionId = $this->request->getPost('session_id');
        $questionId = $this->request->getPost('question_id');
        $answer = $this->request->getPost('answer');
        
        $session = $this->sessionModel->find($sessionId);
        if (!$session || $session['status'] !== 'ongoing' || $session['user_id'] !== session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid session']);
        }
        
        if (strtotime($session['end_time']) < time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Time is up']);
        }
        
        $existingAnswer = $this->answerModel
            ->where('session_id', $sessionId)
            ->where('question_id', $questionId)
            ->first();
        
        if ($existingAnswer) {
            $this->answerModel->update($existingAnswer['id'], [
                'selected_answer' => $answer
            ]);
        } else {
            $this->answerModel->insert([
                'session_id' => $sessionId,
                'question_id' => $questionId,
                'selected_answer' => $answer
            ]);
        }
        
        return $this->response->setJSON(['success' => true, 'message' => 'Answer saved']);
    }
    
    public function getServerTime()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $sessionId = $this->request->getPost('session_id');
        $session = $this->sessionModel->find($sessionId);
        
        if (!$session) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session not found']);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'server_time' => date('Y-m-d H:i:s'),
            'end_time' => $session['end_time']
        ]);
    }
    
    public function finishExam($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);
        
        if (!$session || $session['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/exam')->with('error', 'Invalid session');
        }
        
        $startTime = strtotime($session['start_time']);
        $endTime = time();
        $timeTaken = ($endTime - $startTime) / 60;
        
        $this->sessionModel->update($sessionId, [
            'status' => 'finished',
            'total_time_taken' => round($timeTaken, 2)
        ]);
        
        return redirect()->to('/exam/review/' . $sessionId);
    }
    
    public function review($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);
        
        if (!$session || $session['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/exam')->with('error', 'Invalid session');
        }
        
        $exam = $this->examModel->find($session['exam_id']);
        $questions = $this->questionModel
            ->where('exam_id', $session['exam_id'])
            ->orderBy('order', 'ASC')
            ->findAll();
        
        $answers = $this->answerModel
            ->where('session_id', $sessionId)
            ->findAll();
        
        $answersMap = [];
        foreach ($answers as $answer) {
            $answersMap[$answer['question_id']] = $answer['selected_answer'];
        }
        
        $data = [
            'title' => 'Review: ' . $exam['title'],
            'exam' => $exam,
            'session' => $session,
            'questions' => $questions,
            'answersMap' => $answersMap
        ];
        
        return view('user/review', $data);
    }
}