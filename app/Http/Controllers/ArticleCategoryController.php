<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleCategoryController extends Controller
{
    protected $article_category;

    public function __construct()
    {
        $this->article_category = new ArticleCategoryRepository;
    }

    public function index()
    {
        return view('front.categories.list', ['categories' => $this->article_category->all()->sortBy('name')]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'new_category_name' => 'required|unique:article_category,name'
        ]);

        $category_name = $request->input('new_category_name');
        $this->article_category->create([
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

        $category_old_name = $this->article_category->find($category_id)->name;

        $this->article_category->update([
            'name'            => $request->input('category_name_' . $category_id),
            'updated_user_id' => Auth::user()->id
        ], $category_id);

        $category_name = $this->article_category->find($category_id)->name;

        return redirect('/categories')->with('status', 'Category <b>' . $category_old_name . '</b> has been changed to <b>' . $category_name  . '</b>.');
    }

    public function deleteCategory($category_id)
    {
        $category_name = $this->article_category->find($category_id)->name;
        $this->article_category->delete($category_id);

        return redirect('/categories')->with('status', 'Category <b>' . $category_name . '</b> has been successfully deleted!');
    }
}
