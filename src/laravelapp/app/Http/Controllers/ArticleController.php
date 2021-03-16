<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function showPostingArticle() {
        return view('article.post')
        ->with('user',Auth::user());
    }
}
