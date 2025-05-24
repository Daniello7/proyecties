<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DocumentSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PersonSeeder::class,
            InternalPersonSeeder::class,
            PersonEntrySeeder::class,
            AreaSeeder::class,
            KeySeeder::class,
            KeyControlSeeder::class,
            PackageSeeder::class,
            GuardSeeder::class,
            ZoneSeeder::class,
            GuardZoneSeeder::class,
            GuardReportSeeder::class,
            AlarmSeeder::class,
            AlarmGuardSeeder::class,
        ]);
    }
}
