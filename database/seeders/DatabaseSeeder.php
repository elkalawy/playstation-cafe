<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء جميع الـ Seeders التي تحتاجها هنا
        $this->call([
            UserSeeder::class,
            // يمكنك إضافة seeders أخرى هنا في المستقبل
            // مثلاً: DeviceSeeder::class,
        ]);
    }
}