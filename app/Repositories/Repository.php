<?php

namespace App\Repositories;

use App\Models\Article;

class Repository implements RepositoryInterface
{
    protected $model;

    public function all()
    {
        // Get all articles
        return $this->model->all();
    }

    public function create(array $data)
    {
        $this->model->insert($data);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();
    }

    public function update(array $data, $id)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function paginate($order_by, $entries_per_page)
    {
        return $this->model->orderBy($order_by)->paginate($entries_per_page);
    }
}