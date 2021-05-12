<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mypage\Profile\EditRequest;
use App\Article;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{   
    public function getProfileImage(int $id): string
    {
        $user_data = User::where('id',$id)->first()->toJson();
       
        
        return $user_data;
        
    }
    /*
    マイページ表示
    */
public function showProfile(int $user_id, string $keyword = null)
 {
    $article_count = Article::where('user_id',$user_id)->count();
    $comment_count = Comment::where('user_id',$user_id)->count();

    
    if($keyword === 'user_comments'){

        $comment = Comment::where('user_id',$user_id)->get();
        $comment_id = $comment->pluck('article_id');
        
        $article_list = Article::
        with('user','tags','likes','comments')
        ->ArticleOpen()
        ->whereIn('id',$comment_id)
        ->CreatedAtDescPagenate5();
    
        return  [
            'article_count' => $article_count,
            'comment_count' => $comment_count,
            'article_list'  => $article_list,
        ];
    }
    
    $article_list = Article::with('user','tags','likes','comments')
    ->where('user_id',$user_id)
    ->CreatedAtDescPagenate5();

    return  [
        'article_count' => $article_count,
        'comment_count' => $comment_count,
        'article_list'  => $article_list,
    ];
   
   
 }

    public function showProfileEditForm()
    {  
        $s3_profile_image = User::GetAuthUserImage();

        return view('mypage.profile_edit_form',[
            's3_profile_image' => $s3_profile_image
        ])
        ->with('user',Auth::user());
    }
    
    
    public function editProfile(EditRequest $request) 
    {
         $user = Auth::user();
        
         $user->name = $request->input('name');
        
         if ($request->has('profile_image')) {
             $fileName = $this->saveAvatar($request->file('profile_image'));
             $user->profile_image = 'https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/'.$fileName;
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
  
          $filePath = Storage::disk('s3')
            ->putFile('/', new File($tempPath),'public');

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
