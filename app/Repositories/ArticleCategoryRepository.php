<?php
/**
 * Created by PhpStorm.
 * User: N-150
 * Date: 2018/08/22
 * Time: 16:34
 */

namespace App\Repositories;

use App\Models\ArticleCategory;

class ArticleCategoryRepository extends Repository
{
    public function __construct()
    {
        $this->model = new ArticleCategory;
    }
}