<?php


namespace app\admin\controller;

use think\facade\Cache;

class CheckTokenController
{
  public function __construct()
  {
//    print_r(Cache::get('login'));
  }

  public function checkToken()
  {
    $tokenName = input('get.tokenName');
    $token = input('get.tokenData');
    if (Cache::get($tokenName) == $token) {
      return json('认证成功', 200);
    } else {
      return json('认证失败', 403);
    }

  }

}