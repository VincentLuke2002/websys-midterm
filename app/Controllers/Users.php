<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();

        return view('users/index', [
            'title' => 'User Accounts',
            'users' => $userModel->findAll(),
        ]);
    }

    public function new(): string
    {
        return view('users/form', [
            'title'      => 'New User',
            'heading'    => 'Add New User',
            'formAction' => site_url('users'),
            'user'       => [],
            'isEdit'     => false,
        ]);
    }

    public function create()
    {
        $data = $this->userData();
        $data['password'] = (string) $this->request->getPost('password');
        $data['password_confirm'] = (string) $this->request->getPost('password_confirm');

        if (! $this->validateData($data, $this->userRules(null, true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        unset($data['password_confirm']);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $avatar = $this->request->getFile('avatar');
        if ($avatar !== null && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $this->validate($this->avatarRules())) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $data['avatar'] = $this->saveAvatar($avatar);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        (new UserModel())->insert($data);

        return redirect()->to(site_url('users'))->with('success', 'User account created successfully.');
    }

    public function edit(int $id): string
    {
        $user = (new UserModel())->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('User not found.');
        }

        return view('users/form', [
            'title'      => 'Edit User',
            'heading'    => 'Edit User Account',
            'formAction' => site_url("users/{$id}"),
            'user'       => $user,
            'isEdit'     => true,
        ]);
    }

    public function update(int $id)
    {
        $model = new UserModel();
        $user  = $model->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('User not found.');
        }

        $data = $this->userData();
        $newPassword = (string) $this->request->getPost('password');

        if ($newPassword !== '') {
            $data['password'] = $newPassword;
            $data['password_confirm'] = (string) $this->request->getPost('password_confirm');
        }

        if (! $this->validateData($data, $this->userRules($id, $newPassword !== ''))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($newPassword !== '') {
            unset($data['password_confirm']);
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $avatar = $this->request->getFile('avatar');

        if ($avatar !== null && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $this->validate($this->avatarRules())) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data['avatar'] = $this->saveAvatar($avatar);
        }

        $model->update($id, $data);

        return redirect()->to(site_url('users'))->with('success', 'User account updated successfully.');
    }

    public function delete(int $id)
    {
        if ($id === (int) session('userId')) {
            return redirect()->to(site_url('users'))->with('error', 'You cannot delete the account you are currently using.');
        }

        $model = new UserModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('User not found.');
        }

        try {
            $model->delete($id);
        } catch (DatabaseException) {
            return redirect()->to(site_url('users'))->with('error', 'This staff account has sales history and cannot be deleted.');
        }

        return redirect()->to(site_url('users'))->with('success', 'Staff account deleted successfully.');
    }

    private function userData(): array
    {
        return [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
        ];
    }

    private function userRules(?int $id = null, bool $includePassword = false): array
    {
        $uniqueRule = $id === null
            ? 'is_unique[users.username]'
            : "is_unique[users.username,id,{$id}]";

        $rules = [
            'username' => [
                'label'  => 'Username',
                'rules'  => "required|max_length[50]|alpha_numeric_punct|{$uniqueRule}",
                'errors' => [
                    'required'  => 'Username is required.',
                    'is_unique' => 'That username is already being used.',
                ],
            ],
            'full_name' => [
                'label'  => 'Full name',
                'rules'  => 'required|max_length[100]',
                'errors' => ['required' => 'Full name is required.'],
            ],
        ];

        if ($includePassword) {
            $rules['password'] = [
                'label' => 'Password',
                'rules' => 'required|min_length[8]',
            ];
            $rules['password_confirm'] = [
                'label' => 'Confirm password',
                'rules' => 'required|matches[password]',
                'errors' => ['matches' => 'The password confirmation does not match.'],
            ];
        }

        return $rules;
    }

    private function avatarRules(): array
    {
        return [
            'avatar' => [
                'label' => 'Profile picture',
                'rules' => [
                    'uploaded[avatar]',
                    'max_size[avatar,2048]',
                    'is_image[avatar]',
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                    'ext_in[avatar,jpg,jpeg,png]',
                ],
                'errors' => [
                    'max_size' => 'The profile picture must be 2 MB or smaller.',
                    'is_image' => 'The selected file must be a valid image.',
                    'mime_in'  => 'Only JPG and PNG images are allowed.',
                    'ext_in'   => 'Only JPG and PNG images are allowed.',
                ],
            ],
        ];
    }

    private function saveAvatar($avatar): string
    {
        $extension = strtolower((string) $avatar->guessExtension());
        $filename = bin2hex(random_bytes(16)) . '.' . ($extension === 'jpeg' ? 'jpg' : $extension);
        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        service('image')->withFile($avatar->getTempName())->fit(240, 240, 'center')
            ->save($uploadDir . DIRECTORY_SEPARATOR . $filename, 85);
        return $filename;
    }
}
