<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $table = 'article_category';

    protected $fillable = ['name'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'updated_user_id');
    }

    /**
     * This attribute would allow soft delete
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
