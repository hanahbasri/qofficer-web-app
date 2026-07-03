<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,               // 1. Roles
            UptSeeder::class,                // 2. UPT
            UserSeeder::class,               // 3. Users (butuh roles + upt)
            MasterDataSeeder::class,         // 4. Master target & temuan
            DummyBBKHITDKISeeder::class,     // 5. Dummy data BBKHIT DKI Jakarta
        ]);
    }
}
