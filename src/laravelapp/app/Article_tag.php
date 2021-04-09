<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Tag;

class Article_tag extends Model
{
   

    public function tags() {
        return $this->belogsTo(Tag::class);
    }
}
