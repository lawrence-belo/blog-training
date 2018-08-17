<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ArticleCategory extends Model
{
    protected $table = 'article_category';

    protected $fillable = ['name'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'updated_user_id');
    }
}
