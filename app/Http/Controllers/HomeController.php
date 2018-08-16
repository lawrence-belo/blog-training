<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ['users' =>  User::orderBy('last_name')->paginate(10)]);
    }

    /**
     * Remove user from user list (soft delete: see User model)
     *
     * @param int $user_id
     */
    public function deleteUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $username = $user->username;

        $user->delete();

        return redirect('/home')->with('status', 'User <b>' . $username . '</b> has been successfully deleted!');
    }

    /**
     * Show user update form
     *
     * @param int $user_id
     */
    public function updateUser($user_id)
    {
        //do something
        return view('edit_user', ['user' => User::findOrFail($user_id)]);
    }

    /**
     * Validate and save user updates
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUserUpdates(Request $request)
    {
        $user = User::find($request->input('user_id'));

        $user_data = $request->validate([
            'username'   => [
                'required',
                'min:6',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'password'   => 'nullable|string|min:6|confirmed'
        ]);

        $user->username   = $user_data['username'];
        $user->first_name = $user_data['first_name'];
        $user->last_name  = $user_data['last_name'];
        $user->role       = $request->input('role');

        // only update the password if password field is not empty
        if (!empty($password)) {
            $user->password = bcrypt($user_data['password']);
        }

        $user->save();
        return redirect('update_user/' . $user->id)
            ->with('status', 'User data successfully updated!');
    }
}
