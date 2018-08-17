<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function author()
    {
        return $this->belongsTo('App\User', 'updated_user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\ArticleCategory', 'article_category_id');
    }
}
