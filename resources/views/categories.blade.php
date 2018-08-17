@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">{!! session('status') !!}</div>
        @endif
        <div class="row">
            <h3>Categories</h3>
            <form class="form-inline" method="POST" action="{{ url('/add_category') }}">
                {{ csrf_field() }}
                <input type="text" class="form-control" name="category_name" required>
                <button id="add_category" type="submit" class="btn btn-primary">Add Category</button>
            </form>
            <table class="table">
                <thead>
                <tr>
                    <th>Category</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                <form class="form-inline" method="POST" action="{{ url('/update_category/' . $category->id ) }}">
                                    {{ csrf_field() }}
                                    <input type="text" class="form-control" name="category_name" value="{{ $category->name }}" required>
                                    <button type="submit" class="update_category btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>{{ $category->owner()->first()->username }}</td>
                            <td>{{ $category->created_at }}</td>
                            <td>{{ $category->updated_at }}</td>
                            <td>
                                <a href="{{ url('delete_category/' . $category->id) }}" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
