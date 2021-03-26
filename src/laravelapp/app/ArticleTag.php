<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroup extends Pivot
{
    protected $table = 'article_tag';

    public function role()
    {
        return $this->belongsTo(Tag::class);
    }
}