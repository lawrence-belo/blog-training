<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function create(array $data)
    {
        // encrypt password
        $data['password'] = bcrypt($data['password']);

        $this->model->insert($data);
    }

    public function update(array $data, $id)
    {
        $record = $this->model->findOrFail($id);

        // only update the password if password field is not empty
        if (isset($data['password']) && !empty($data['password'])) {
            // encrypt password
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $record->update($data);
    }
}