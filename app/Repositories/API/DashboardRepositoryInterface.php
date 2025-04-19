<?php

namespace App\Repositories\API;

interface DashboardRepositoryInterface
{
    // Define the methods your repository should implement
    public function getAllItems();
    public function upcomingEvents();
    public function upcomingOrders();
}
