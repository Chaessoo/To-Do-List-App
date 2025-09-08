<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categories extends Model
{

    use Hasfactory;
    
    protected $filable = [
        'name'
    ];

    /**
     * Get all of the categories for the Categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Tasks::class);
    }
}
