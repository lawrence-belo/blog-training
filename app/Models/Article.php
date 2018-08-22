<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = ['article_category_id', 'title', 'slug', 'contents', 'image_path', 'updated_user_id'];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'updated_user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ArticleCategory', 'article_category_id');
    }

    /**
     * This attribute would allow soft delete
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
