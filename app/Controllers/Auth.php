<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        helper(['form', 'url']);
        $this->userModel = new UserModel();
    }
    
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]',
            ];
            
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                $user = $this->userModel->where('email', $email)->first();
                
                if ($user && password_verify($password, $user['password'])) {
                    session()->regenerate();
                    
                    $sessionData = [
                        'user_id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                    ];
                    
                    session()->set($sessionData);
                    
                    if ($user['role'] === 'admin') {
                        return redirect()->to('/admin');
                    } else {
                        return redirect()->to('/exam');
                    }
                }
                
                return redirect()->back()->with('error', 'Invalid credentials');
            }
        }
        
        return view('auth/login', ['title' => 'Login']);
    }
    
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'matches[password]',
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                    'role' => 'user',
                ];
                
                $this->userModel->insert($data);
                
                return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
            }
        }
        
        return view('auth/register', ['title' => 'Register']);
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}