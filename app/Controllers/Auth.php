<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function attempt()
    {
        $credentials = [
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
        ];

        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];

        if (! $this->validateData($credentials, $rules)) {
            return redirect()->to(site_url('login'))->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->where('username', $credentials['username'])->first();

        if ($user === null || ! password_verify($credentials['password'], $user['password'])) {
            return redirect()->to(site_url('login'))->withInput()->with('error', 'Invalid username or password.');
        }

        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'username'   => $user['username'],
            'fullName'   => $user['full_name'],
        ]);

        return redirect()->to(site_url('dashboard'))->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'))->with('success', 'You have been logged out.');
    }
}
