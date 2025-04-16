<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @method static \Illuminate\Database\Eloquent\Builder|static firstOrCreate(array $attributes = [], array $values = [])
 */
class Role extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
