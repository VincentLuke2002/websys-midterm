<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;

class Products extends BaseController
{
    public function index(): string
    {
        return view('products/index', [
            'title' => 'Products',
            'products' => (new ProductModel())->orderBy('name')->findAll(),
        ]);
    }

    public function new(): string
    {
        return view('products/form', [
            'title' => 'New Product', 'heading' => 'Add New Product',
            'formAction' => site_url('products'), 'product' => [],
        ]);
    }

    public function create()
    {
        $data = $this->productData();
        if (! $this->validateData($data, $this->productRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        if ($image !== null && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $this->validate($this->imageRules())) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $data['image'] = $this->saveImage($image);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        (new ProductModel())->insert($data);
        return redirect()->to(site_url('products'))->with('success', 'Product created successfully.');
    }

    public function edit(int $id): string
    {
        $product = (new ProductModel())->find($id);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Product not found.');
        }
        return view('products/form', [
            'title' => 'Edit Product', 'heading' => 'Edit Product',
            'formAction' => site_url("products/{$id}"), 'product' => $product,
        ]);
    }

    public function update(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Product not found.');
        }

        $data = $this->productData();
        if (! $this->validateData($data, $this->productRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        if ($image !== null && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $this->validate($this->imageRules())) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $data['image'] = $this->saveImage($image);
        }

        $model->update($id, $data);
        return redirect()->to(site_url('products'))->with('success', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('Product not found.');
        }
        try {
            $model->delete($id);
        } catch (DatabaseException) {
            return redirect()->to(site_url('products'))->with('error', 'This product has sales history and cannot be deleted.');
        }
        return redirect()->to(site_url('products'))->with('success', 'Product deleted successfully.');
    }

    private function productData(): array
    {
        return [
            'name' => trim((string) $this->request->getPost('name')),
            'price' => trim((string) $this->request->getPost('price')),
            'stock_quantity' => trim((string) $this->request->getPost('stock_quantity')),
        ];
    }

    private function productRules(): array
    {
        return [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];
    }

    private function imageRules(): array
    {
        return ['image' => ['label' => 'Product image', 'rules' => [
            'uploaded[image]', 'max_size[image,2048]', 'is_image[image]',
            'mime_in[image,image/jpg,image/jpeg,image/png]', 'ext_in[image,jpg,jpeg,png]',
        ]]];
    }

    private function saveImage($image): string
    {
        $extension = strtolower((string) $image->guessExtension());
        $filename = bin2hex(random_bytes(16)) . '.' . ($extension === 'jpeg' ? 'jpg' : $extension);
        $directory = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'products';
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        service('image')->withFile($image->getTempName())->fit(640, 480, 'center')
            ->save($directory . DIRECTORY_SEPARATOR . $filename, 85);
        return $filename;
    }
}
