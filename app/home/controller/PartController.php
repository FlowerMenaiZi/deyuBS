<?php


namespace app\home\controller;
use think\facade\Db;

class PartController
{
  public function getPartList(){
    $result = Db::table('part')->where('semester',input('get.semester'))->withoutField('id,semester')->select();
    return json($result,200);
  }
}