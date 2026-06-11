<?php
namespace App\Models;

use CodeIgniter\Model;

class ExamRegistrationModel extends Model
{
    protected $table = 'exam_registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'exam_id', 'registered_at'];
    protected $useTimestamps = true;
    protected $createdField = 'registered_at';
}