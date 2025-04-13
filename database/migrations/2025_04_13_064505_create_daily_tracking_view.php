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
    public function up()
{
    DB::statement("
        CREATE OR REPLACE VIEW daily_tracking_view AS
SELECT
    dt.id AS tracking_id,
    e.id AS event_id,
    ed.id AS event_day_id,
    e.event_name,
    e.start_date,
    dt.day_number,
    i.id AS item_id,
    i.item_name,
    i.current_quantity,
    i.min_stock_level,
    i.max_stock_level,
    ei.planned_quantity,
    dt.projected_usage,
    dt.buffer_percentage,
    dt.remaining_start,
    dt.picked,
    dt.used,
    dt.remaining_end,
    s.supplier_name
FROM daily_trackings dt
JOIN events e ON dt.event_id = e.id
JOIN event_days ed ON dt.event_day_id = ed.id
JOIN inventories i ON dt.item_id = i.id
JOIN event_inventory_assignments ei
    ON ei.event_id = dt.event_id AND ei.item_id = dt.item_id
LEFT JOIN suppliers s ON i.supplier_id = s.supplier_id;
    ");
}

public function down()
{
    DB::statement("DROP VIEW IF EXISTS daily_tracking_view");
}

};
