<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Admin;
use App\Models\Field;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'name' => 'Admin Futsal ID',
            'email' => 'admin@futsalid.com',
            'password' => bcrypt('admin123'),
            'staff_id' => 'STF001',
        ]);

        // Create Sample Members
        Member::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890',
            'points' => 50,
        ]);

        Member::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567891',
            'points' => 30,
        ]);

        // Create Sample Fields
        Field::create([
            'name' => 'Lapangan A - Premium',
            'type' => 'Sintetis',
            'price_per_hour' => 150000,
            'status' => 'available',
            'description' => 'Lapangan premium dengan rumput sintetis berkualitas tinggi, dilengkapi lampu sorot LED.',
        ]);

        Field::create([
            'name' => 'Lapangan B - Standard',
            'type' => 'Vinyl',
            'price_per_hour' => 100000,
            'status' => 'available',
            'description' => 'Lapangan standar dengan vinyl berkualitas, cocok untuk latihan tim.',
        ]);

        Field::create([
            'name' => 'Lapangan C - Tournament',
            'type' => 'Sintetis',
            'price_per_hour' => 200000,
            'status' => 'available',
            'description' => 'Lapangan tournament grade, standar FIFA untuk pertandingan resmi.',
        ]);

        Field::create([
            'name' => 'Lapangan D - Mini Soccer',
            'type' => 'Vinyl',
            'price_per_hour' => 80000,
            'status' => 'available',
            'description' => 'Lapangan mini soccer untuk anak-anak dan latihan skill.',
        ]);

        Field::create([
            'name' => 'Lapangan E - Maintenance',
            'type' => 'Sintetis',
            'price_per_hour' => 150000,
            'status' => 'maintenance',
            'description' => 'Lapangan sedang dalam perawatan, akan tersedia minggu depan.',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin: admin@futsalid.com | Password: admin123');
        $this->command->info('📧 Member: john@example.com | Password: password');
    }
}
