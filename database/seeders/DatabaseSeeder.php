<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GroundStation;
use App\Models\Satellite;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@persatelitan.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular user
        User::create([
            'name' => 'User Demo',
            'email' => 'user@persatelitan.id',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Ground Stations
        $gs1 = GroundStation::create([
            'name' => 'Stasiun Bumi Cibinong',
            'location' => 'Cibinong, Jawa Barat',
            'country' => 'Indonesia',
            'latitude' => -6.4930,
            'longitude' => 106.8318,
            'description' => 'Stasiun bumi utama LAPAN di Cibinong',
            'is_active' => true,
        ]);

        $gs2 = GroundStation::create([
            'name' => 'Kennedy Space Center',
            'location' => 'Florida',
            'country' => 'Amerika Serikat',
            'latitude' => 28.5721,
            'longitude' => -80.6480,
            'description' => 'Pusat peluncuran NASA',
            'is_active' => true,
        ]);

        $gs3 = GroundStation::create([
            'name' => 'Baikonur Cosmodrome',
            'location' => 'Baikonur, Kazakhstan',
            'country' => 'Rusia',
            'latitude' => 45.9208,
            'longitude' => 63.3422,
            'description' => 'Kosmodrom Baikonur milik Rusia',
            'is_active' => true,
        ]);

        // Satellites
        Satellite::create([
            'ground_station_id' => $gs1->id,
            'name' => 'LAPAN-A3',
            'country' => 'Indonesia',
            'launch_date' => '2016-06-22',
            'orbit_type' => 'LEO',
            'tle_line1' => '1 41557U 16040B   24001.50000000  .00001234  00000-0  54321-4 0  9999',
            'tle_line2' => '2 41557  97.9832 180.0000 0001234  90.0000 270.0000 14.80000000000010',
            'is_active' => true,
            'description' => 'Satelit observasi bumi milik LAPAN Indonesia',
        ]);

        Satellite::create([
            'ground_station_id' => $gs2->id,
            'name' => 'GPS IIF-12',
            'country' => 'Amerika Serikat',
            'launch_date' => '2016-02-05',
            'orbit_type' => 'MEO',
            'is_active' => true,
            'description' => 'Satelit navigasi GPS generasi IIF',
        ]);

        Satellite::create([
            'ground_station_id' => $gs2->id,
            'name' => 'Telkom-4',
            'country' => 'Indonesia',
            'launch_date' => '2018-08-07',
            'orbit_type' => 'GEO',
            'is_active' => true,
            'description' => 'Satelit telekomunikasi milik Telkom Indonesia',
        ]);

        Satellite::create([
            'ground_station_id' => $gs3->id,
            'name' => 'Sputnik-1 Heritage',
            'country' => 'Rusia',
            'launch_date' => '1957-10-04',
            'orbit_type' => 'LEO',
            'is_active' => false,
            'description' => 'Satelit bersejarah pertama di dunia (replika data)',
        ]);

        Satellite::create([
            'ground_station_id' => $gs2->id,
            'name' => 'Starlink-1234',
            'country' => 'Amerika Serikat',
            'launch_date' => '2023-01-15',
            'orbit_type' => 'LEO',
            'is_active' => true,
            'description' => 'Satelit internet broadband SpaceX Starlink',
        ]);
    }
}