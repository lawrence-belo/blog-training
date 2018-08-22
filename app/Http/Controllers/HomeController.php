<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = new UserRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users', ['users' =>  $this->user->paginate('last_name', 10)]);
    }

    public function addUser()
    {
        return view('admin.add_user');
    }

    /**
     * Remove user from user list (soft delete: see User model)
     *
     * @param int $user_id
     */
    public function deleteUser($user_id)
    {
        $username = $this->user->find($user_id)->username;
        $this->user->delete($user_id);

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
        return view('admin.edit_user', ['user' => $this->user->find($user_id)]);
    }

    /**
     * Add a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewUser(Request $request)
    {
        $user_data = $request->validate([
            'username'   => [
                'required',
                'min:6',
                'max:255',
                Rule::unique('users')
            ],
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'password'   => 'required|string|min:6|confirmed'
        ]);

        $this->user->create($request->only([
            'username',
            'first_name',
            'last_name',
            'role',
            'password'
        ]));

        return redirect('/home')
            ->with('status', 'User ' . $user_data['username'] . ' successfully created!');
    }

    /**
     * Validate and save user updates
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUserUpdates(Request $request)
    {
        $user_id = $request->input('user_id');
        $request->validate([
            'username'   => [
                'required',
                'min:6',
                'max:255',
                Rule::unique('users')->ignore($user_id)
            ],
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'password'   => 'nullable|string|min:6|confirmed'
        ]);

        $this->user->update($request->only([
            'username',
            'first_name',
            'last_name',
            'role',
            'password'
        ]), $request->input('user_id'));

        return redirect('update_user/' . $user_id)
            ->with('status', 'User data successfully updated!');
    }
}
