<?php

namespace App\Services\API;

use App\Repositories\API\DashboardRepositoryInterface;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * The repository instance.
     *
     * @var \App\Repositories\API\DashboardRepositoryInterface
     */
    protected DashboardRepositoryInterface $dashboardRepository;

    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\API\DashboardRepositoryInterface  $dashboardRepository
     * @return void
     */
    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    /**
     * Example method to get dashboard data.
     *
     * @return array
     */
    // Your service logic goes here

    public function getAllItems()
    {
        // Call the repository method to get all items
        try
        {
            return $this->dashboardRepository->getAllItems();
        } catch (\Exception $e) {
            Log::error("DashboardService@getAllItems: " . $e->getMessage());
            // Handle exceptions as needed
            throw $e;
        }
    }

    public function upcomingEvents()
    {
        // Call the repository method to get all items
        try
        {
            return $this->dashboardRepository->upcomingEvents();
        } catch (\Exception $e) {
            Log::error("DashboardService@getAllItems: " . $e->getMessage());
            // Handle exceptions as needed
            throw $e;
        }
    }

    public function upcomingOrders()
    {
        // Call the repository method to get all items
        try
        {
            return $this->dashboardRepository->upcomingOrders();
        } catch (\Exception $e) {
            Log::error("DashboardService@getAllItems: " . $e->getMessage());
            // Handle exceptions as needed
            throw $e;
        }
    }
}
