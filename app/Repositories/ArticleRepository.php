<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Article;
    }
}