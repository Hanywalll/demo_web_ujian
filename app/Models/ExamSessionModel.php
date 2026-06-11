<?php
use CodeIgniter\Model;

class ExamSessionModel extends Model
{
    protected $table = 'exam_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'exam_id', 'start_time', 'end_time', 'status', 'total_time_taken'];
}