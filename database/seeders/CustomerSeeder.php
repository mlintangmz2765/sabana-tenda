<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $linkedUsers = User::where('role', User::ROLE_CUSTOMER)->get()->keyBy('username');

        $customers = [
            // Linked to user accounts
            ['code' => 'CUST-001', 'user' => 'budi_customer', 'name' => 'Budi Santoso', 'phone' => '0812-4444-0001', 'email' => 'budi@example.com', 'addr' => 'Jl. Kaliurang KM 5, Sleman, Yogyakarta', 'ktp' => '3404012345670001'],
            ['code' => 'CUST-002', 'user' => 'siti_customer', 'name' => 'Siti Aisyah', 'phone' => '0812-4444-0002', 'email' => 'siti@example.com', 'addr' => 'Jl. Magelang KM 8, Yogyakarta', 'ktp' => '3404012345670002'],
            ['code' => 'CUST-003', 'user' => 'andi_customer', 'name' => 'Andi Pratama', 'phone' => '0812-4444-0003', 'email' => 'andi@example.com', 'addr' => 'Jl. Solo KM 10, Yogyakarta', 'ktp' => '3404012345670003'],
            ['code' => 'CUST-004', 'user' => 'dewi_customer', 'name' => 'Dewi Lestari', 'phone' => '0812-4444-0004', 'email' => 'dewi@example.com', 'addr' => 'Jl. Wates KM 6, Bantul', 'ktp' => '3404012345670004'],
            ['code' => 'CUST-005', 'user' => 'ricky_customer', 'name' => 'Ricky Fadli', 'phone' => '0812-4444-0005', 'email' => 'ricky@example.com', 'addr' => 'Jl. Parangtritis KM 7, Bantul', 'ktp' => '3404012345670005'],

            // Walk-in customers (no login account)
            ['code' => 'CUST-006', 'user' => null, 'name' => 'Faisal Rahman', 'phone' => '0812-5555-0001', 'email' => null, 'addr' => 'Sleman, Yogyakarta', 'ktp' => '3404012345670006'],
            ['code' => 'CUST-007', 'user' => null, 'name' => 'Nurul Hidayah', 'phone' => '0812-5555-0002', 'email' => null, 'addr' => 'Bantul, Yogyakarta', 'ktp' => '3404012345670007'],
            ['code' => 'CUST-008', 'user' => null, 'name' => 'Galang Wicaksono', 'phone' => '0812-5555-0003', 'email' => null, 'addr' => 'Kulon Progo, Yogyakarta', 'ktp' => '3404012345670008'],
            ['code' => 'CUST-009', 'user' => null, 'name' => 'Putri Maharani', 'phone' => '0812-5555-0004', 'email' => 'putri@example.com', 'addr' => 'Gunungkidul, Yogyakarta', 'ktp' => '3404012345670009'],
            ['code' => 'CUST-010', 'user' => null, 'name' => 'Hendra Kusuma', 'phone' => '0812-5555-0005', 'email' => null, 'addr' => 'Sleman, Yogyakarta', 'ktp' => '3404012345670010'],
            ['code' => 'CUST-011', 'user' => null, 'name' => 'Mega Sari', 'phone' => '0812-5555-0006', 'email' => null, 'addr' => 'Kota Yogyakarta', 'ktp' => '3404012345670011'],
            ['code' => 'CUST-012', 'user' => null, 'name' => 'Iwan Setiawan', 'phone' => '0812-5555-0007', 'email' => null, 'addr' => 'Magelang, Jawa Tengah', 'ktp' => '3308012345670001'],
        ];

        foreach ($customers as $row) {
            Customer::create([
                'customer_code' => $row['code'],
                'user_id' => $row['user'] ? $linkedUsers[$row['user']]?->id : null,
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'address' => $row['addr'],
                'id_card_type' => 'KTP',
                'id_card_number' => $row['ktp'],
            ]);
        }
    }
}
