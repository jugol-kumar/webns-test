<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $id)
 * @method static when(mixed $status, \Closure $param)
 */
class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'attachment',
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
