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
     * Show a WYSYWIG editor for updating an article
     *
     * @param int $article_id
     */
    public function editArticle($article_id)
    {
        return view('edit_article', [
            'categories' => ArticleCategory::orderBy('name')->get(),
            'article'    => Article::findOrFail($article_id)
        ]);
    }

    /**
     * Save new article data to database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewArticle(Request $request)
    {
        $request->validate([
            'blog_title'    => 'required|max:255',
            'category'      => 'required',
            'slug'          => 'required|alpha_dash',
            'blog_contents' => 'required'
        ]);

        Article::insert([
            'article_category_id' => $request->input('category'),
            'title'               => $request->input('blog_title'),
            'slug'                => $request->input('slug'),
            'contents'            => $request->input('blog_contents'),
            'image_path'          => $request->input('image_path'),
            'updated_user_id'     => Auth::user()->id
        ]);

        return redirect('/articles')->with('status', 'Blog post <b>' . $request->input('blog_title') . '</b> has been successfully saved!');
    }

    /**
     * Save updated article data to database
     *
     * @param Request $request
     * @param $article_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateArticle(Request $request, $article_id)
    {
        $request->validate([
            'blog_title'    => 'required|max:255',
            'category'      => 'required',
            'slug'          => 'required|alpha_dash',
            'blog_contents' => 'required'
        ]);

        $article = Article::findOrFail($article_id);
        $article->article_category_id = $request->input('category');
        $article->title               = $request->input('blog_title');
        $article->slug                = $request->input('slug');
        $article->contents            = $request->input('blog_contents');
        $article->image_path          = $request->input('image_path');
        $article->updated_user_id     = Auth::user()->id;

        $article->save();
        return redirect('/articles')->with('status', 'Blog post <b>' . $request->input('blog_title') . '</b> has been successfully updated!');
    }

    /**
     * Remove article from list (soft-delete)
     *
     * @param $article_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteArticle($article_id)
    {
        $article = Article::findOrFail($article_id);
        $title = $article->title;

        $article->delete();

        return redirect('/articles')->with('status', 'Blog post <b>' . $title . '</b> has been successfully deleted!');
    }
}
