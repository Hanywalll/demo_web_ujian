<?php
namespace App\Models;

use CodeIgniter\Model;

class UserAnswerModel extends Model
{
    protected $table = 'user_answers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session_id', 'question_id', 'selected_answer'];
}