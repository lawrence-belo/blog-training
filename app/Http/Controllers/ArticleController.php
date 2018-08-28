<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
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
        return view('front.articles.list', ['articles' => $this->article->all()->sortByDesc('created_at')]);
    }

    /**
     * Show a WYSYWIG editor for creating articles
     */
    public function createArticle()
    {
        return view('front.articles.create_article', ['categories' => $this->article_category->all()->sortBy('name')]);
    }

    /**
     * Show a WYSYWIG editor for updating an article
     *
     * @param int $article_id
     * @return View
     */
    public function editArticle($article_id)
    {
        return view('front.articles.edit_article', [
            'categories' => $this->article_category->all()->sortBy('name'),
            'article'    => $this->article->find($article_id)
        ]);
    }

    /**
     * Save new article data to database
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewArticle(ArticleRequest $request)
    {
        $this->article->create([
            'article_category_id' => $request->input('category'),
            'title'               => $request->input('title'),
            'slug'                => $request->input('slug'),
            'contents'            => $request->input('contents'),
            'image_path'          => $request->input('image_path'),
            'updated_user_id'     => Auth::user()->id
        ]);

        return redirect('/articles')->with('status', 'Blog post <b>' . $request->input('title') . '</b> has been successfully saved!');
    }

    /**
     * Save updated article data to database
     *
     * @param ArticleRequest $request
     * @param $article_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateArticle(ArticleRequest $request, $article_id)
    {
        $this->article->update([
            'article_category_id' => $request->input('category'),
            'title'               => $request->input('title'),
            'slug'                => $request->input('slug'),
            'contents'            => $request->input('contents'),
            'image_path'          => $request->input('image_path'),
            'updated_user_id'     => Auth::user()->id
        ], $article_id);

        return redirect('/articles')->with('status', 'Blog post <b>' . $request->input('title') . '</b> has been successfully updated!');
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
