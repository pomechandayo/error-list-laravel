<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Article_tag extends Pivot
{
    
    public function get_tags() {
        return $this->hasMany(Tag::class);
    }
}
