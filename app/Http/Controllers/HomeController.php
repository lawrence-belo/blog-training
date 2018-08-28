<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;

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
        return view('admin.users.list', ['users' =>  $this->user->paginate('last_name', 10)]);
    }

    public function addUser()
    {
        return view('admin.users.add_user');
    }

    /**
     * Remove user from user list (soft delete: see User model)
     *
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateUser($user_id)
    {
        return view('admin.users.edit_user', ['user' => $this->user->find($user_id)]);
    }

    /**
     * Add a new user
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewUser(UserRequest $request)
    {
        $this->user->create($request->only([
            'username',
            'first_name',
            'last_name',
            'role',
            'password'
        ]));

        return redirect('/home')
            ->with('status', 'User ' . $request->input('username') . ' successfully created!');
    }

    /**
     * Validate and save user updates
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUserUpdates(UserRequest $request)
    {
        $user_id = $request->input('user_id');

        $this->user->update($request->only([
            'username',
            'first_name',
            'last_name',
            'role',
            'password'
        ]), $user_id);

        return redirect('update_user/' . $user_id)
            ->with('status', 'User data successfully updated!');
    }
}
