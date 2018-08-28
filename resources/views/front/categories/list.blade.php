@extends('common.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">{!! session('status') !!}</div>
        @endif
        <div class="row">
            <h3>Categories</h3>
            <form class="form-inline" method="POST" action="{{ url('/add_category') }}">
                {{ csrf_field() }}
                <input type="text" class="form-control" name="new_category_name" value="{{ old('new_category_name') }}" required>
                <button id="add_category" type="submit" class="btn btn-primary">Add Category</button>
                @if ($errors->has('new_category_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('new_category_name') }}</strong>
                    </span>
                @endif
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
                                    <input type="text" class="form-control" name="category_name_{{ $category->id }}" value="{{ old('category_name', $category->name) }}" required>
                                    <button type="submit" class="update_category btn btn-sm btn-primary">Update</button>
                                    @if ($errors->has('category_name_'.$category->id))
                                        <span class="help-block">
                                            <strong>The category name has already been taken.</strong>
                                        </span>
                                    @endif
                                </form>
                            </td>
                            <td>{{ $category->owner()->first()->username }}</td>
                            <td>{{ $category->created_at }}</td>
                            <td>{{ $category->updated_at }}</td>
                            <td>
                                <a href="{{ url('delete_category/' . $category->id) }}" class="delete_category btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
