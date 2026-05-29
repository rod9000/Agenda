<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['name' => 'Limpeza de Pele',     'duration_min' => 60,  'price' => 150.00, 'color_hex' => '#3b82f6'],
            ['name' => 'Massagem Relaxante',   'duration_min' => 60,  'price' => 120.00, 'color_hex' => '#22c55e'],
            ['name' => 'Peeling Químico',       'duration_min' => 45,  'price' => 200.00, 'color_hex' => '#eab308'],
            ['name' => 'Drenagem Linfática',   'duration_min' => 60,  'price' => 130.00, 'color_hex' => '#ec4899'],
            ['name' => 'Microagulhamento',     'duration_min' => 90,  'price' => 250.00, 'color_hex' => '#8b5cf6'],
            ['name' => 'Laser Facial',         'duration_min' => 30,  'price' => 180.00, 'color_hex' => '#f97316'],
            ['name' => 'Hidratação Facial',    'duration_min' => 45,  'price' => 100.00, 'color_hex' => '#06b6d4'],
            ['name' => 'Depilação a Laser',    'duration_min' => 30,  'price' => 80.00,  'color_hex' => '#ef4444'],
        ];

        foreach ($services as $s) {
            Service::create($s);
        }
    }
}
