<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW historical_usage_view AS
            SELECT
                i.id AS item_id,
                i.item_name,
                s.supplier_name,
                COUNT(DISTINCT dt.event_id) AS total_events,
                GROUP_CONCAT(DISTINCT e.event_name ORDER BY e.event_name) AS event_names,
                SUM(dt.used) AS total_used,
                SUM(ei.planned_quantity) AS total_planned,
                ROUND(AVG(dt.used), 2) AS avg_used_per_day,
                ROUND(AVG(ei.planned_quantity), 2) AS avg_planned_per_event,
                ROUND(AVG(dt.used) * 1.10, 2) AS recommended_plan_with_buffer,
                e.event_category_id, -- Add event_category_id from the events table
                ec.name AS event_category_name -- Add event category name from event_categories table
            FROM daily_trackings dt
            JOIN inventories i ON dt.item_id = i.id
            JOIN event_inventory_assignments ei
                ON ei.event_id = dt.event_id AND ei.item_id = dt.item_id
            LEFT JOIN suppliers s ON i.supplier_id = s.supplier_id
            LEFT JOIN events e ON dt.event_id = e.id
            LEFT JOIN event_categories ec ON e.event_category_id = ec.id -- Join with event_categories table to get the name
            GROUP BY i.id, i.item_name, s.supplier_name, e.event_category_id, ec.name
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS historical_usage_view");
    }
};
