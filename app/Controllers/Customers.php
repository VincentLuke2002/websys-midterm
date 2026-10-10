<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    public function index(): string
    {
        $customerModel = new CustomerModel();

        return view('customers/index', [
            'title'     => 'Customer Accounts',
            'customers' => $customerModel->findAll(),
        ]);
    }

    public function new(): string
    {
        return view('customers/form', [
            'title'      => 'New Customer',
            'heading'    => 'Add New Customer',
            'formAction' => site_url('customers'),
            'customer'   => [],
        ]);
    }

    public function create()
    {
        $data = $this->customerData();

        if (! $this->validateData($data, $this->customerRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        (new CustomerModel())->insert($data);

        return redirect()->to(site_url('customers'))->with('success', 'Customer account created successfully.');
    }

    public function edit(int $id): string
    {
        $customer = (new CustomerModel())->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        return view('customers/form', [
            'title'      => 'Edit Customer',
            'heading'    => 'Edit Customer Account',
            'formAction' => site_url("customers/{$id}"),
            'customer'   => $customer,
        ]);
    }

    public function update(int $id)
    {
        $model = new CustomerModel();

        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        $data = $this->customerData();

        if (! $this->validateData($data, $this->customerRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, $data);

        return redirect()->to(site_url('customers'))->with('success', 'Customer account updated successfully.');
    }

    public function delete(int $id)
    {
        $model = new CustomerModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        try {
            $model->delete($id);
        } catch (DatabaseException) {
            return redirect()->to(site_url('customers'))->with('error', 'This customer could not be deleted.');
        }

        return redirect()->to(site_url('customers'))->with('success', 'Customer deleted successfully.');
    }

    private function customerData(): array
    {
        return [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => trim((string) $this->request->getPost('phone')),
        ];
    }

    private function customerRules(): array
    {
        return [
            'full_name' => [
                'label'  => 'Full name',
                'rules'  => 'required|max_length[100]',
                'errors' => ['required' => 'Full name is required.'],
            ],
            'email' => [
                'label'  => 'Email address',
                'rules'  => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required'    => 'Email address is required.',
                    'valid_email' => 'Enter a valid email address.',
                ],
            ],
            'phone' => [
                'label' => 'Phone number',
                'rules' => 'permit_empty|max_length[20]',
            ],
        ];
    }
}
