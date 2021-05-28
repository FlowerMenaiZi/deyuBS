<?php


namespace app\admin\controller;
use think\facade\Db;

class GetUserController
{
  public function getUser(){
    $result = Db::table('user')->field('stuid,bedNumName,dormitory')->order('bedNumName')->select();
    $midUserArr = ["7405"=>[],"7406"=>[],"7407"=>[],"7408"=>[],"6515"=>[]];
    foreach ($result as $item){
      if (array_key_exists($item['dormitory'], $midUserArr)) {
        $temArr['stuId']=$item['stuid'];
        $temArr['name']=$item['bedNumName'];
        array_push($midUserArr[$item['dormitory']],$temArr);
      }
    }
    return json($midUserArr,200);
  }

}