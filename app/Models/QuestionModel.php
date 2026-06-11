<?php
namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['exam_id', 'question_text', 'image_path', 'options', 'correct_answer', 'order'];
}