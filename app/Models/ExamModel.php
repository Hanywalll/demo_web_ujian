<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 
        'description', 
        'duration_minutes', 
        'total_questions', 
        'status'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}