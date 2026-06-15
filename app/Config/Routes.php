<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// Admin routes
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    
    // ✅ EXAMS MANAGEMENT
    $routes->get('exams', 'Admin::exams');
    $routes->get('exams/create', 'Admin::createExam');
    $routes->post('exams/create', 'Admin::createExam');
    $routes->get('exams/(:num)/publish', 'Admin::publishExam/$1');
    $routes->get('exams/(:num)/unpublish', 'Admin::unpublishExam/$1');
    
    // ✅ QUESTIONS MANAGEMENT
    $routes->get('exams/(:num)/questions', 'Admin::questions/$1');
    $routes->get('exams/(:num)/questions/add', 'Admin::addQuestion/$1');
    $routes->post('exams/(:num)/questions/add', 'Admin::addQuestion/$1');
    
    // ✅ EXTRA TIME
    $routes->get('add-extra-time', 'Admin::addExtraTime');
    $routes->post('add-extra-time', 'Admin::addExtraTime');
    
    // ✅ AJAX ENDPOINTS (untuk real-time polling)
    $routes->post('get-dashboard-data', 'Admin::getDashboardData');
    $routes->post('get-extra-time-data', 'Admin::getExtraTimeData');
    $routes->post('get-user-exam-history/(:num)', 'Admin::getUserExamHistory/$1');
});

// Exam routes (untuk user)
$routes->group('exam', ['filter' => 'auth:user'], function($routes) {
    $routes->get('/', 'Exam::index');
    $routes->get('register/(:num)', 'Exam::register/$1');
    $routes->get('start/(:num)', 'Exam::start/$1');
    $routes->get('take/(:num)/question/(:num)', 'Exam::takeExam/$1/$2');
    $routes->post('save-answer', 'Exam::saveAnswer');
    $routes->post('get-server-time', 'Exam::getServerTime');
    $routes->post('get-exams-data', 'Exam::getExamsData'); 
    $routes->get('finish/(:num)', 'Exam::finishExam/$1');
    $routes->get('review/(:num)', 'Exam::review/$1');
});