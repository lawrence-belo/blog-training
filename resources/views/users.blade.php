@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{!! session('status') !!}</div>
    @endif
    <div class="row">
        <h3>User List</h3>
        <a href="{{ url('/add_user') }}" class="btn btn-primary">Add User</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Created At</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>
                            <a href="{{ url('update_user/' . $user->id) }}" class="btn btn-xs btn-primary">Update</a>
                            <a href="{{ url('delete_user/' . $user->id) }}" class="btn btn-xs btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>
@endsection
