<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PosSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $password = password_hash('Password123!', PASSWORD_DEFAULT);
        $this->db->table('products')->insertBatch([
            ['name'=>'Classic Milk Tea','price'=>95.00,'stock_quantity'=>40,'created_at'=>$now],
            ['name'=>'Iced Spanish Latte','price'=>135.00,'stock_quantity'=>28,'created_at'=>$now],
            ['name'=>'Chocolate Cookie','price'=>65.00,'stock_quantity'=>4,'created_at'=>$now],
            ['name'=>'Chicken Sandwich','price'=>145.00,'stock_quantity'=>18,'created_at'=>$now],
            ['name'=>'Bottled Water','price'=>35.00,'stock_quantity'=>50,'created_at'=>$now],
        ]);
        $this->db->table('customers')->insertBatch([
            ['full_name'=>'Customer One','email'=>'customer1@example.com','phone'=>'0917-000-0001','created_at'=>$now],
            ['full_name'=>'Customer Two','email'=>'customer2@example.com','phone'=>'0917-000-0002','created_at'=>$now],
            ['full_name'=>'Customer Three','email'=>'customer3@example.com','phone'=>'0917-000-0003','created_at'=>$now],
            ['full_name'=>'Customer Four','email'=>'customer4@example.com','phone'=>'0917-000-0004','created_at'=>$now],
            ['full_name'=>'Customer Five','email'=>'customer5@example.com','phone'=>'0917-000-0005','created_at'=>$now],
        ]);
        $this->db->table('users')->insertBatch([
            ['username'=>'admin01','full_name'=>'Store Administrator','password'=>$password,'created_at'=>$now],
            ['username'=>'cashier01','full_name'=>'Cashier One','password'=>$password,'created_at'=>$now],
            ['username'=>'cashier02','full_name'=>'Cashier Two','password'=>$password,'created_at'=>$now],
            ['username'=>'manager01','full_name'=>'Store Manager','password'=>$password,'created_at'=>$now],
            ['username'=>'stock01','full_name'=>'Stock Clerk','password'=>$password,'created_at'=>$now],
        ]);
    }
}
