<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Traits\Models;

use App\Models\Comment;

/**
     * Class CommentableTrait
 * @package App\Models\Traits
 */
trait CommentableTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user', 'user.info');
    }

    public function scopeCountComments($query)
    {
        $query->leftJoin(\DB::raw('(SELECT commentable_id, commentable_type, COUNT(commentable_id) as comments_count FROM `comments`) comments'), function($join){
            $join->on('comments.commentable_id', '=', $this::getTable() . '.id')
                ->where('comments.commentable_type', '=', 'App\\Models\\' . class_basename($this));
        })
        ->select(
            $this::getTable(). '.*',
            'comments_count'
        );
    }
}
