<?php

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('/exams', 'Admin::exams');
    $routes->match(['get', 'post'], '/exams/create', 'Admin::createExam');
    $routes->get('/exams/(:num)/questions', 'Admin::questions/$1');
    $routes->match(['get', 'post'], '/exams/(:num)/questions/add', 'Admin::addQuestion/$1');
    $routes->match(['get', 'post'], '/add-extra-time', 'Admin::addExtraTime');
});

$routes->group('exam', ['filter' => 'auth:user'], function($routes) {
    $routes->get('/', 'Exam::index');
    $routes->get('/register/(:num)', 'Exam::register/$1');
    $routes->get('/start/(:num)', 'Exam::start/$1');
    $routes->get('/take/(:num)/question/(:num)', 'Exam::takeExam/$1/$2');
    $routes->post('/save-answer', 'Exam::saveAnswer');
    $routes->post('/get-server-time', 'Exam::getServerTime');
    $routes->get('/finish/(:num)', 'Exam::finishExam/$1');
    $routes->get('/review/(:num)', 'Exam::review/$1');
});