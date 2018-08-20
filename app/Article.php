<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    public function author()
    {
        return $this->belongsTo('App\User', 'updated_user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\ArticleCategory', 'article_category_id');
    }

    /**
     * This attribute would allow soft delete
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
