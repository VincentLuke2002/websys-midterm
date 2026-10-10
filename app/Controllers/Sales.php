<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class Sales extends BaseController
{
    public function index(): string
    {
        return view('sales/index', [
            'title' => 'Sales History',
            'sales' => (new SaleModel())->history(),
        ]);
    }

    public function new(): string
    {
        return view('sales/form', [
            'title' => 'Record Sale',
            'products' => (new ProductModel())->where('stock_quantity >', 0)->orderBy('name')->findAll(),
            'customers' => (new CustomerModel())->orderBy('full_name')->findAll(),
        ]);
    }

    public function create()
    {
        $data = [
            'product_id' => (string) $this->request->getPost('product_id'),
            'customer_id' => (string) $this->request->getPost('customer_id'),
            'quantity' => (string) $this->request->getPost('quantity'),
        ];
        $rules = [
            'product_id' => 'required|is_natural_no_zero',
            'customer_id' => 'permit_empty|is_natural_no_zero',
            'quantity' => 'required|is_natural_no_zero',
        ];
        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productModel = new ProductModel();
        $product = $productModel->find((int) $data['product_id']);
        if ($product === null) {
            return redirect()->back()->withInput()->with('error', 'The selected product does not exist.');
        }
        if ($data['customer_id'] !== '' && (new CustomerModel())->find((int) $data['customer_id']) === null) {
            return redirect()->back()->withInput()->with('error', 'The selected customer does not exist.');
        }

        $quantity = (int) $data['quantity'];
        $db = db_connect();
        $db->transBegin();
        $db->table('products')->where('id', (int) $product['id'])
            ->where('stock_quantity >=', $quantity)
            ->set('stock_quantity', "stock_quantity - {$quantity}", false)->update();

        if ($db->affectedRows() !== 1) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', "Not enough stock. Only {$product['stock_quantity']} unit(s) are available.");
        }

        (new SaleModel())->insert([
            'product_id' => (int) $product['id'],
            'customer_id' => $data['customer_id'] === '' ? null : (int) $data['customer_id'],
            'sold_by' => (int) session('userId'),
            'quantity' => $quantity,
            'total_price' => round((float) $product['price'] * $quantity, 2),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'The sale could not be saved. Please try again.');
        }
        $db->transCommit();
        return redirect()->to(site_url('sales'))->with('success', 'Sale recorded and inventory updated.');
    }
}
