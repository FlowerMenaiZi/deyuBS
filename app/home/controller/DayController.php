<?php


namespace app\home\controller;

use think\facade\Db;
class DayController
{

  public function getDayList(){
    $result = Db::table('day')->where('dateTime',input('get.dateTime'))->withoutField('id,dateTime')->select();
    return json($result,200);
  }

}