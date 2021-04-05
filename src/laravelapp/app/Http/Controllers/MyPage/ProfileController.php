<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mypage\Profile\EditRequest;
use App\Article;
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
public function showProfile(Request $request) {

    $user = Auth::user();

    $article_count = Article::where('user_id',$user->id)->get();
    
    // myarticleの値が入っていれば自分が投稿した記事一覧をviewに送る
    if($request->menu_link === 'myarticle')
    {
        $sort = $request->sort;
        $article_list = Article::where('user_id',$user->id)->orderBy('created_at', 'desc')->Paginate(5);
        
        return view('mypage.profile',[
            'article_list' => $article_list,
            'sort' => $sort,
            'article_count' => $article_count,
            ])->with('user',Auth::user());
        }else{
            return view('mypage.profile',[
            'article_count' => $article_count,
            ])
            ->with('user',Auth::user());
        }
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
