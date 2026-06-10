<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Users table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'user'], 'default' => 'user'],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
        
        // Exams table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'duration_minutes' => ['type' => 'INT', 'default' => 60],
            'total_questions' => ['type' => 'INT', 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published'], 'default' => 'draft'],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('exams');
        
        // Questions table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'exam_id' => ['type' => 'INT'],
            'question_text' => ['type' => 'TEXT'],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'options' => ['type' => 'JSON'],
            'correct_answer' => ['type' => 'VARCHAR', 'constraint' => 1],
            'order' => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('questions');
        
        // Exam registrations table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT'],
            'exam_id' => ['type' => 'INT'],
            'registered_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'exam_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_registrations');
        
        // Exam sessions table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT'],
            'exam_id' => ['type' => 'INT'],
            'start_time' => ['type' => 'TIMESTAMP', 'null' => true],
            'end_time' => ['type' => 'TIMESTAMP', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['ongoing', 'finished'], 'default' => 'ongoing'],
            'total_time_taken' => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_sessions');
        
        // User answers table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'session_id' => ['type' => 'INT'],
            'question_id' => ['type' => 'INT'],
            'selected_answer' => ['type' => 'VARCHAR', 'constraint' => 1, 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['session_id', 'question_id']);
        $this->forge->addForeignKey('session_id', 'exam_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_answers');
    }

    public function down()
    {
        $this->forge->dropTable('user_answers');
        $this->forge->dropTable('exam_sessions');
        $this->forge->dropTable('exam_registrations');
        $this->forge->dropTable('questions');
        $this->forge->dropTable('exams');
        $this->forge->dropTable('users');
    }
}