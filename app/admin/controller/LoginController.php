<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\Cache;

class LoginController
{

  public $tokenT;
  public function checkLogin()
  {
    $isTrue = Db::table('admin_user')
                  ->where(['name' => input('post.user'), 'password' => input('post.pass')])
                  ->find();
    $date=date_create();
    Cache::set('login',md5(date_timestamp_get($date)),1200);
    $this->tokenT = Cache::get('login');
    if ($isTrue) {
      $data = [
        'msg' => '登录成功',
        "data" => 'success',
        "token"=>$this->tokenT
      ];
      return json($data,200);
    }else{
      $errorData = [
        'msg' => '登录失败',
        "data"  => 'error'
      ];
      return json($errorData,401);
    }
  }
}