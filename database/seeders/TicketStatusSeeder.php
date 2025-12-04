<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get default ticket statuses from config
        $statuses = [
            ['name' => 'Open', 'color' => '#6c757d', 'order' => 1, 'status' => 1],
            ['name' => 'Assigned', 'color' => '#0d6efd', 'order' => 2, 'status' => 1],
            ['name' => 'Returned By QA', 'color' => '#dc3545', 'order' => 3, 'status' => 1],
            ['name' => 'In Progress', 'color' => '#0d6efd', 'order' => 4, 'status' => 1],
            ['name' => 'Done', 'color' => '#198754', 'order' => 5, 'status' => 1],
            ['name' => 'Ready for Qa (Dev)', 'color' => '#ffc107', 'order' => 6, 'status' => 1],
            ['name' => 'Verified By QA (DEV)', 'color' => '#20c997', 'order' => 7, 'status' => 1],
            ['name' => 'Ready For QA (Production)', 'color' => '#fd7e14', 'order' => 8, 'status' => 1],
            ['name' => 'Verified By QA (Production)', 'color' => '#198754', 'order' => 9, 'status' => 1],
            ['name' => 'Closed', 'color' => '#495057', 'order' => 10, 'status' => 1],
            ['name' => 'On Hold', 'color' => '#6c757d', 'order' => 11, 'status' => 1],
        ];

        foreach ($statuses as $status) {
            TicketStatus::updateOrCreate(['name' => $status['name']], $status);
        }
    }
}
