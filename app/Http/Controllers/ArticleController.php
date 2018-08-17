<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles', ['articles' => Article::orderBy('created_at', 'desc')->get()]);
    }

    /**
     * Show a WYSYWIG editor for creating articles
     */
    public function createArticle()
    {
        return view('create_article', ['categories' => ArticleCategory::orderBy('name')->get()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewArticle(Request $request)
    {
        $request->validate([
            'blog_title' => 'required|max:255',
            'category'   => 'required',
            'slug'       => 'required|alpha_dash',
            'blog_contents'   => 'required'
        ]);

        Article::insert([
            'article_category_id' => $request->input('category'),
            'title'               => $request->input('blog_title'),
            'slug'                => $request->input('slug'),
            'contents'            => $request->input('blog_contents'),
            'updated_user_id'     => Auth::user()->id
        ]);

        return redirect('/articles')->with('status', 'Blog post <b>' . $request->input('blog_title') . '</b> has been successfully saved!');
    }
}
