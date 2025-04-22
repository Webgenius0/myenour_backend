<?php

namespace App\Repositories\API;

interface EventCategoryRepositoryInterface
{
    // Define the methods your repository should implement
    public function getEventCategories();
    public function storeEventCategory(array $eventCategoryData);
    public function updateEventCategory($id, array $eventCategoryData);
    public function deleteEventCategory($id);
    public function getEventCategoryById($id);
}
