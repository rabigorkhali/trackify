<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get default ticket statuses from config
        $statuses = config('cmsConfig.default_ticket_statuses', [
            ['name' => 'To Do', 'color' => '#6c757d', 'order' => 1, 'status' => 1],
            ['name' => 'In Progress', 'color' => '#0d6efd', 'order' => 2, 'status' => 1],
            ['name' => 'In Review', 'color' => '#ffc107', 'order' => 3, 'status' => 1],
            ['name' => 'Done', 'color' => '#198754', 'order' => 4, 'status' => 1],
            ['name' => 'Blocked', 'color' => '#dc3545', 'order' => 5, 'status' => 1],
        ]);

        foreach ($statuses as $status) {
            TicketStatus::updateOrCreate(['name' => $status['name']], $status);
        }
    }
}

