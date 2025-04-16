<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'user'],
            ['name' => 'organizer'],
            ['name' => 'ticket_checker'],
        ]);
    }
}

