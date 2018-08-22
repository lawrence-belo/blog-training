<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleCategoryRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected $article;
    protected $article_category;

    public function __construct()
    {
        $this->article = new ArticleRepository;
        $this->article_category = new ArticleCategoryRepository;
    }

    public function index()
    {
        return view('front.articles', ['articles' => $this->article->all()->sortByDesc('created_at')]);
    }

    /**
     * Show a WYSYWIG editor for creating articles
     */
    public function createArticle()
    {
        return view('front.create_article', ['categories' => $this->article_category->all()->sortBy('name')]);
    }

    /**
     * Show a WYSYWIG editor for updating an article
     *
     * @param int $article_id
     */
    public function editArticle($article_id)
    {
        return view('front.edit_article', [
            'categories' => $this->article_category->all()->sortBy('name'),
            'article'    => $this->article->find($article_id)
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

        $this->article->create([
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

        $this->article->update([
            'article_category_id' => $request->input('category'),
            'title'               => $request->input('blog_title'),
            'slug'                => $request->input('slug'),
            'contents'            => $request->input('blog_contents'),
            'image_path'          => $request->input('image_path'),
            'updated_user_id'     => Auth::user()->id
        ], $article_id);

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
        $title = $this->article->find($article_id)->title;
        $this->article->delete($article_id);

        return redirect('/articles')->with('status', 'Blog post <b>' . $title . '</b> has been successfully deleted!');
    }
}
