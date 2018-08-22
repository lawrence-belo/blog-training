<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all();

    /**
     * Create a record
     * @param array $data
     */
    public function create(array $data);

    /**
     * Update a record
     * @param array $data
     * @param int $id
     */
    public function update(array $data, $id);

    /**
     * Delete a record
     * @param $id
     */
    public function delete($id);

    /**
     * Get specific record
     * @param $id
     */
    public function find($id);

    /**
     * Get paginated results
     * @param string $order_by Default order
     * @param int $entries_per_page Entries per page
     */
    public function paginate($order_by, $entries_per_page);
}