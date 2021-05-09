<?php

namespace App\Http\Middleware;

use Closure;

class KeywordExploder
{
    /**
     * キーワードを空白文字で分割して配列にする
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $keyword = $request->input('keyword');
        $ret = [];
    
        if(!empty($keyword)){
            if(!is_string($keyword))
            {
                $keyword = implode('',$keyword);
            }
            // +を半角スペースに変換
            $keyword = str_replace('+','',$keyword);
            // 全角スペースを半角スペースに変換
            $keyword = str_replace('　','',$keyword);
            // %はSQL実行時にLIKEのパラメーターとして使うのでスペースにする
            $keyword = str_replace('%','',$keyword);
            // 取得したキーワードのスペースの重複を除く
            $keyword = preg_replace('/\s(?=\s)/','',$keyword);
            // キーワード文字列の前後のスペースを削除する
            $keyword = trim($keyword);
         

        }

        if(!empty($keyword) || $keyword !== '')
        {
            // 半角カナを全角カナへ変換
            $keyword = mb_convert_kana($keyword,'KV');
            // 半角スペースで配列にし、重複は削除する
            $ret['keyword'] = array_unique(explode(' ',$keyword));
            

        }
       
        $request->merge($ret);
        return $next($request);
    }
}
