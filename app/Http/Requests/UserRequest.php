<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules for user creation/update.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->input('user_id');

        if ($user_id) {
            $unique_rule = Rule::unique('users')->ignore($user_id);
            $password_rule = 'nullable|string|min:6|confirmed';
        } else {
            $unique_rule = Rule::unique('users');
            $password_rule = 'required|string|min:6|confirmed';
        }

        return [
            'username'   => [
                'required',
                'min:6',
                'max:255',
                $unique_rule
            ],
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'password'   => $password_rule
        ];
    }
}
