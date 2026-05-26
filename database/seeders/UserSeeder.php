<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // OWNER
            [
                'user_code' => 'USR-001',
                'name' => 'Pak Bambang Sutrisno',
                'username' => 'owner_sabana',
                'email' => 'owner@sabanatenda.id',
                'phone' => '0812-1111-0001',
                'password' => 'OwnerSabana123',
                'role' => User::ROLE_OWNER,
            ],

            // ADMIN
            [
                'user_code' => 'USR-002',
                'name' => 'Destiana Wicaksani',
                'username' => 'admin_sabana',
                'email' => 'admin@sabanatenda.id',
                'phone' => '0812-2222-0001',
                'password' => 'AdminSabana123',
                'role' => User::ROLE_ADMIN,
            ],

            // STAFF
            [
                'user_code' => 'USR-003',
                'name' => 'Love\'s Nurani Hasan',
                'username' => 'staff_sabana',
                'email' => 'staff1@sabanatenda.id',
                'phone' => '0812-3333-0001',
                'password' => 'StaffSabana123',
                'role' => User::ROLE_STAFF,
            ],
            [
                'user_code' => 'USR-004',
                'name' => 'M Lintang Maulana Zulfan',
                'username' => 'staff_lintang',
                'email' => 'staff2@sabanatenda.id',
                'phone' => '0812-3333-0002',
                'password' => 'StaffLintang123',
                'role' => User::ROLE_STAFF,
            ],
            [
                'user_code' => 'USR-005',
                'name' => 'Rina Permatasari',
                'username' => 'staff_rina',
                'email' => 'staff3@sabanatenda.id',
                'phone' => '0812-3333-0003',
                'password' => 'StaffRina123',
                'role' => User::ROLE_STAFF,
            ],

            // CUSTOMERS
            [
                'user_code' => 'USR-006',
                'name' => 'Budi Santoso',
                'username' => 'budi_customer',
                'email' => 'budi@example.com',
                'phone' => '0812-4444-0001',
                'password' => 'BudiCustomer123',
                'role' => User::ROLE_CUSTOMER,
            ],
            [
                'user_code' => 'USR-007',
                'name' => 'Siti Aisyah',
                'username' => 'siti_customer',
                'email' => 'siti@example.com',
                'phone' => '0812-4444-0002',
                'password' => 'SitiCustomer123',
                'role' => User::ROLE_CUSTOMER,
            ],
            [
                'user_code' => 'USR-008',
                'name' => 'Andi Pratama',
                'username' => 'andi_customer',
                'email' => 'andi@example.com',
                'phone' => '0812-4444-0003',
                'password' => 'AndiCustomer123',
                'role' => User::ROLE_CUSTOMER,
            ],
            [
                'user_code' => 'USR-009',
                'name' => 'Dewi Lestari',
                'username' => 'dewi_customer',
                'email' => 'dewi@example.com',
                'phone' => '0812-4444-0004',
                'password' => 'DewiCustomer123',
                'role' => User::ROLE_CUSTOMER,
            ],
            [
                'user_code' => 'USR-010',
                'name' => 'Ricky Fadli',
                'username' => 'ricky_customer',
                'email' => 'ricky@example.com',
                'phone' => '0812-4444-0005',
                'password' => 'RickyCustomer123',
                'role' => User::ROLE_CUSTOMER,
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                ...$userData,
                'password' => Hash::make($userData['password']),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
