<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $db = db_connect();
        $today = date('Y-m-d');
        $todaySales = $db->table('sales')->selectSum('total_price', 'total')
            ->where('created_at >=', $today . ' 00:00:00')->get()->getRowArray();

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'productCount' => $db->table('products')->countAllResults(),
            'customerCount' => $db->table('customers')->countAllResults(),
            'saleCount' => $db->table('sales')->countAllResults(),
            'lowStockCount' => $db->table('products')->where('stock_quantity <=', 5)->countAllResults(),
            'todayTotal' => (float) ($todaySales['total'] ?? 0),
        ]);
    }
}
