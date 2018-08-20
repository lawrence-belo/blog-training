<?php

namespace App\Http\Controllers;

use App\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ArticleCategoryController extends Controller
{
    public function index()
    {
        return view('categories', ['categories' => ArticleCategory::orderBy('name')->get()]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'new_category_name' => 'required|unique:article_category,name'
        ]);

        $category_name = $request->input('new_category_name');
        ArticleCategory::insert([
            'name'            => $category_name,
            'updated_user_id' => Auth::user()->id
        ]);

        return redirect('/categories')->with('status', 'Category <b>' . $category_name . '</b> has been successfully added!');
    }

    public function updateCategory(Request $request, $category_id)
    {
        $request->validate([
            'category_name_' . $category_id => 'required|unique:article_category,name'
        ]);

        $category = ArticleCategory::findOrFail($category_id);
        $category_old_name = $category->name;

        $category->name            = $request->input('category_name_' . $category_id);
        $category->updated_user_id = Auth::user()->id;

        $category->save();

        return redirect('/categories')->with('status', 'Category <b>' . $category_old_name . '</b> has been changed to <b>' . $category->name  . '</b>.');
    }

    public function deleteCategory($category_id)
    {
        $category = ArticleCategory::findOrFail($category_id);
        $category_name = $category->name;
        $category->delete();

        return redirect('/categories')->with('status', 'Category <b>' . $category_name . '</b> has been successfully deleted!');
    }
}
