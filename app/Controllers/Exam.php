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
            $registration = $this->registrationModel
                ->where('user_id', $userId)
                ->where('exam_id', $exam['id'])
                ->first();
            
            $exam['registered'] = $registration ? true : false;
            
            // Ambil session terbaru
            if ($registration) {
                $exam['latest_session'] = $this->sessionModel
                    ->where('user_id', $userId)
                    ->where('exam_id', $exam['id'])
                    ->orderBy('id', 'DESC')
                    ->first();
            } else {
                $exam['latest_session'] = null;
            }
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
            
            return redirect()->to('/exam')->with('success', 'Berhasil mendaftar ujian');
        }
        
        return redirect()->to('/exam')->with('error', 'Anda sudah terdaftar');
    }
    
    public function start($examId)
    {
        $userId = session()->get('user_id');
        $exam = $this->examModel->find($examId);
        
        if (!$exam || $exam['status'] !== 'published') {
            return redirect()->to('/exam')->with('error', 'Exam not available');
        }
        
        $registration = $this->registrationModel
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->first();
        
        if (!$registration) {
            return redirect()->to('/exam')->with('error', 'Please register first');
        }
        
        // Cari SEMUA session untuk user ini dan exam ini
        $allSessions = $this->sessionModel
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->whereIn('status', ['ongoing', 'finished', 'expired'])
            ->findAll();
        
        // Cek apakah ada session ongoing yang masih valid
        $validSession = null;
        foreach ($allSessions as $session) {
            if ($session['status'] === 'ongoing' && strtotime($session['end_time']) > time()) {
                $validSession = $session;
                break;
            }
        }
        
        // Update semua session ongoing yang sudah expired
        foreach ($allSessions as $session) {
            if ($session['status'] === 'ongoing' && strtotime($session['end_time']) <= time()) {
                $this->sessionModel->update($session['id'], ['status' => 'expired']);
            }
        }
        
        if ($validSession) {
            // Lanjutkan session yang masih valid
            $sessionId = $validSession['id'];
        } else {
            // Buat session baru
            $startTime = date('Y-m-d H:i:s');
            $endTime = date('Y-m-d H:i:s', strtotime('+' . $exam['duration_minutes'] . ' minutes'));
            
            $sessionId = $this->sessionModel->insert([
                'user_id' => $userId,
                'exam_id' => $examId,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'ongoing'
            ]);
        }
        
        $firstQuestion = $this->questionModel
            ->where('exam_id', $examId)
            ->orderBy('order', 'ASC')
            ->first();
        
        if (!$firstQuestion) {
            return redirect()->to('/exam')->with('error', 'No questions available');
        }
        
        return redirect()->to('/exam/take/' . $sessionId . '/question/' . $firstQuestion['id']);
    }
    
    public function takeExam($sessionId, $questionId = null)
    {
        $session = $this->sessionModel->find($sessionId);
        
        if (!$session) {
            return redirect()->to('/exam')->with('error', 'Sesi tidak ditemukan');
        }
        
        if ($session['status'] !== 'ongoing') {
            return redirect()->to('/exam')->with('error', 'Ujian sudah selesai');
        }
        
        if ($session['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/exam')->with('error', 'Akses tidak sah');
        }
        
        // Cek waktu
        if (!empty($session['end_time'])) {
            $endTime = strtotime($session['end_time']);
            $now = time();
            
            if ($endTime <= $now) {
                $this->sessionModel->update($sessionId, [
                    'status' => 'finished',
                    'total_time_taken' => 0
                ]);
                
                return redirect()->to('/exam/review/' . $sessionId)->with('warning', 'Waktu ujian telah habis');
            }
        } else {
            $exam = $this->examModel->find($session['exam_id']);
            $durationMinutes = (int)$exam['duration_minutes'];
            
            if ($durationMinutes <= 0) {
                $durationMinutes = 60;
            }
            
            $newEndTime = date('Y-m-d H:i:s', strtotime("+{$durationMinutes} minutes"));
            $this->sessionModel->update($sessionId, ['end_time' => $newEndTime]);
            $session['end_time'] = $newEndTime;
        }
        
        $exam = $this->examModel->find($session['exam_id']);
        
        if (!$exam) {
            return redirect()->to('/exam')->with('error', 'Data ujian tidak ditemukan');
        }
        
        $questions = $this->questionModel
            ->where('exam_id', $session['exam_id'])
            ->orderBy('order', 'ASC')
            ->findAll();
        
        if (empty($questions)) {
            return redirect()->to('/exam')->with('error', 'Ujian ini belum memiliki soal');
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
        'csrf_name' => csrf_token(),
        // 🔥 KIRIM TIMESTAMP END_TIME (dalam milidetik)
        'endTimestamp' => strtotime($session['end_time']) * 1000,
        // 🔥 KIRIM JUGA END_TIME MENTAH
        'endTimeRaw' => $session['end_time']
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
        $isDoubtful = $this->request->getPost('is_doubtful') ? 1 : 0;
        
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
        
        $data = [
            'selected_answer' => $answer,
            'is_doubtful' => $isDoubtful
        ];
        
        if ($existingAnswer) {
            $this->answerModel->update($existingAnswer['id'], $data);
        } else {
            $data['session_id'] = $sessionId;
            $data['question_id'] = $questionId;
            $this->answerModel->insert($data);
        }
        
        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Answer saved',
            'csrf_token' => csrf_hash()
        ]);
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
            'end_time' => $session['end_time'],
            'csrf_token' => csrf_hash()
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
<<<<<<< HEAD
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
        
        // ✅ FIX: Build answersMap dengan is_doubtful juga
        $answersMap = [];
        foreach ($answers as $answer) {
            $answersMap[$answer['question_id']] = [
                'selected_answer' => $answer['selected_answer'],
                'is_doubtful' => $answer['is_doubtful'] ?? 0
            ];
        }
        
        // ✅ HITUNG SKOR
        $totalQuestions = count($questions);
        $correctCount = 0;
        $wrongCount = 0;
        $emptyCount = 0;
        $doubtfulCount = 0;
        $questionResults = [];
        
        foreach ($questions as $question) {
            // ✅ FIX: Reset $result setiap iterasi
            $result = [];
            
            // ✅ FIX: Set data soal ke $result
            $result['question'] = $question;
            
            // Ambil jawaban user
            $answerData = $answersMap[$question['id']] ?? null;
            $userAnswer = $answerData['selected_answer'] ?? null;
            $isDoubtful = $answerData['is_doubtful'] ?? 0;
            $correctAnswer = $question['correct_answer'];
            
            $result['user_answer'] = $userAnswer;
            $result['correct_answer'] = $correctAnswer;
            $result['is_doubtful'] = $isDoubtful;
            
            // Tentukan status
            if ($userAnswer === null || $userAnswer === '') {
                $emptyCount++;
                $result['status'] = 'empty';
            } elseif ($isDoubtful) {
                $doubtfulCount++;
                if ($userAnswer === $correctAnswer) {
                    $correctCount++;
                    $result['status'] = 'doubtful_correct';
                } else {
                    $wrongCount++;
                    $result['status'] = 'doubtful_wrong';
                }
            } elseif ($userAnswer === $correctAnswer) {
                $correctCount++;
                $result['status'] = 'correct';
            } else {
                $wrongCount++;
                $result['status'] = 'wrong';
            }
            
            $questionResults[] = $result;
        }
        
        // Hitung nilai (0-100)
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        
        // Tentukan grade
        $grade = 'E';
        if ($score >= 90) $grade = 'A';
        elseif ($score >= 80) $grade = 'B';
        elseif ($score >= 70) $grade = 'C';
        elseif ($score >= 60) $grade = 'D';
        
        $data = [
            'title' => 'Review: ' . $exam['title'],
            'exam' => $exam,
            'session' => $session,
            'questions' => $questions,
            'answersMap' => $answersMap,
            'questionResults' => $questionResults,
            'totalQuestions' => $totalQuestions,
            'correctCount' => $correctCount,
            'wrongCount' => $wrongCount,
            'emptyCount' => $emptyCount,
            'doubtfulCount' => $doubtfulCount,
            'score' => $score,
            'grade' => $grade
        ];
        
        return view('user/review', $data);
    }
    // ✅ METHOD BARU: AJAX endpoint untuk real-time update daftar ujian
public function getExamsData()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403);
    }
    
    $userId = session()->get('user_id');
    
    // Ambil semua exam yang published
    $exams = $this->examModel->where('status', 'published')->findAll();
    
    $formattedExams = [];
    foreach ($exams as $exam) {
        // Cek registrasi
        $registration = $this->registrationModel
            ->where('user_id', $userId)
            ->where('exam_id', $exam['id'])
            ->first();
        
        $registered = $registration ? true : false;
        
        // Ambil session terbaru
        $latestSession = null;
        $buttonType = 'register'; // register, start, continue, review
        $buttonLabel = 'Register';
        $buttonIcon = 'bi-pencil-square';
        $buttonUrl = base_url('exam/register/' . $exam['id']);
        $buttonClass = 'btn-primary';
        $score = null;
        
        if ($registered) {
            $latestSession = $this->sessionModel
                ->where('user_id', $userId)
                ->where('exam_id', $exam['id'])
                ->orderBy('id', 'DESC')
                ->first();
            
            if ($latestSession) {
                $isOngoing = ($latestSession['status'] === 'ongoing' && strtotime($latestSession['end_time']) > time());
                
                if ($isOngoing) {
                    $buttonType = 'continue';
                    $buttonLabel = 'Lanjutkan Ujian';
                    $buttonIcon = 'bi-play-circle';
                    $buttonUrl = base_url('exam/start/' . $exam['id']);
                    $buttonClass = 'btn-warning';
                } elseif (in_array($latestSession['status'], ['finished', 'expired'])) {
                    $buttonType = 'review';
                    $buttonLabel = 'Lihat Hasil';
                    $buttonIcon = 'bi-eye';
                    $buttonUrl = base_url('exam/review/' . $latestSession['id']);
                    $buttonClass = 'btn-secondary';
                    $score = $latestSession['score'] ?? null;
                } else {
                    $buttonType = 'start';
                    $buttonLabel = 'Mulai Ujian';
                    $buttonIcon = 'bi-play-circle';
                    $buttonUrl = base_url('exam/start/' . $exam['id']);
                    $buttonClass = 'btn-success';
                }
            } else {
                $buttonType = 'start';
                $buttonLabel = 'Mulai Ujian';
                $buttonIcon = 'bi-play-circle';
                $buttonUrl = base_url('exam/start/' . $exam['id']);
                $buttonClass = 'btn-success';
            }
        }
        
        $formattedExams[] = [
            'id' => $exam['id'],
            'title' => $exam['title'],
            'description' => $exam['description'],
            'duration_minutes' => $exam['duration_minutes'],
            'total_questions' => $exam['total_questions'],
            'registered' => $registered,
            'button_type' => $buttonType,
            'button_label' => $buttonLabel,
            'button_icon' => $buttonIcon,
            'button_url' => $buttonUrl,
            'button_class' => $buttonClass,
            'score' => $score,
            'header_class' => $registered ? 'bg-success text-white' : 'bg-primary text-white',
        ];
    }
    
    return $this->response->setJSON([
        'success' => true,
        'exams' => $formattedExams,
        'csrf_token' => csrf_hash()
    ]);
=======
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
    
    // ✅ HITUNG SKOR
    $totalQuestions = count($questions);
    $correctCount = 0;
    $wrongCount = 0;
    $emptyCount = 0;
    $doubtfulCount = 0;
    $questionResults = [];
    
    foreach ($questions as $question) {
        $result = [];
        $result['question'] = $question;
        
        $answerData = $answersMap[$question['id']] ?? null;
        $userAnswer = $answerData['selected_answer'] ?? null;
        $isDoubtful = $answerData['is_doubtful'] ?? 0;
        $correctAnswer = $question['correct_answer'];
        
        $result['user_answer'] = $userAnswer;
        $result['correct_answer'] = $correctAnswer;
        $result['is_doubtful'] = $isDoubtful;
        
        if ($userAnswer === null || $userAnswer === '') {
            $emptyCount++;
            $result['status'] = 'empty';
        } elseif ($isDoubtful) {
            $doubtfulCount++;
            if ($userAnswer === $correctAnswer) {
                $correctCount++;
                $result['status'] = 'doubtful_correct';
            } else {
                $wrongCount++;
                $result['status'] = 'doubtful_wrong';
            }
        } elseif ($userAnswer === $correctAnswer) {
            $correctCount++;
            $result['status'] = 'correct';
        } else {
            $wrongCount++;
            $result['status'] = 'wrong';
        }
        
        $questionResults[] = $result;
    }
    
    // ✅ HITUNG NILAI (0-100)
    $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
    
    // ✅ TENTUKAN GRADE
    $grade = 'E';
    if ($score >= 90) $grade = 'A';
    elseif ($score >= 80) $grade = 'B';
    elseif ($score >= 70) $grade = 'C';
    elseif ($score >= 60) $grade = 'D';
    
    // ✅ KIRIM SEMUA DATA KE VIEW
    $data = [
        'title' => 'Review: ' . $exam['title'],
        'exam' => $exam,
        'session' => $session,
        'questions' => $questions,
        'answersMap' => $answersMap,
        'questionResults' => $questionResults,
        'totalQuestions' => $totalQuestions,
        'correctCount' => $correctCount,
        'wrongCount' => $wrongCount,
        'emptyCount' => $emptyCount,
        'doubtfulCount' => $doubtfulCount,
        'score' => $score,      // ✅ PASTIKAN ADA
        'grade' => $grade       // ✅ PASTIKAN ADA
    ];
    
    return view('user/review', $data);
>>>>>>> userfeature
}
}