<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mypage\Profile\EditRequest;
use App\Article;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{   
    /*
    マイページ表示
    */
public function showProfile(Request $request)
 {
    $user = Auth::user();
    $comment_id = [];
    $article_list = [];
    $article_count = Article::where('user_id',$user->id)->get();
    $comment_count = Comment::where('user_id',Auth::id())->get();
    
    // myarticleの値が入っていれば自分が投稿した記事一覧をviewに送る
    if($request->menu_link === 'myarticle_all') {
        $sort = $request->sort;
        $article_list = Article::with('user','tags','likes','comments')
        ->where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->Paginate(5);
        }
    // 公開中の記事だけ表示
    if($request->menu_link === 'myarticle_open') {
        $sort = $request->sort;
        $article_list = Article::with('user','tags','likes','comments')
        ->ArticleOpen()
        ->where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->Paginate(5);   
        }
    // 非公開中の記事だけ表示
    if($request->menu_link === 'myarticle_closed') {
        $sort = $request->sort;
        $article_list = Article::with('user','tags','likes','comments')
        ->ArticleClosed()
        ->where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->Paginate(5);
        }
    // ユーザーがコメントした記事だけ表示
    if($request->menu_link === 'my_comment_article') {
        $sort = $request->sort;
        $comment = Comment::where('user_id',Auth::id())->get();
        $comment_id = $comment->pluck('article_id');
       
        $article_list = Article::
        with('user','tags','likes','comments')
        ->ArticleOpen()
        ->whereIn('id',$comment_id)
        ->orderBy('created_at', 'desc')
        ->Paginate(5);
    }
    
         return view('mypage.profile',[
            'article_count' => $article_count,
            'comment_count' => $comment_count,
            'comment_id' => $comment_id,
            'article_list' => $article_list,
            ])->with('user',Auth::user());
        
}

    public function showProfileEditForm()
    {   
        return view('mypage.profile_edit_form')
        ->with('user',Auth::user());
    }
    
    
    public function editProfile(EditRequest $request) 
    {
         $user = Auth::user();
        
         $user->name = $request->input('name');
        
         if ($request->has('profile_image')) {
             $fileName = $this->saveAvatar($request->file('profile_image'));
             $user->profile_image= $fileName;
         }

        $user->save();

        return redirect()->back()
        ->with('status','プロフィールを変更しました');
    }
    /**
      * アバター画像をリサイズして保存します
      *
      * @param UploadedFile $file アップロードされたアバター画像
      * @return string ファイル名
      */
      private function saveAvatar(UploadedFile $file): string
      {
          $tempPath = $this->makeTempPath();
  
          Image::make($file)->fit(200, 200)->save($tempPath);
  
          $filePath = Storage::disk('public')
            ->putFile('profile_image', new File($tempPath));

          return basename($filePath);
      }
     
         /**
      * 一時的なファイルを生成してパスを返します。
      *
      * @return string ファイルパス
      */
     private function makeTempPath():string
     {
         $tmp_fp = tmpfile();
         $meta   = stream_get_meta_data($tmp_fp);
         return $meta["uri"];
     }
    }
