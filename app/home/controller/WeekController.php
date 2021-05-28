<?php


namespace app\home\controller;

use think\facade\Db;

class WeekController
{
  public $thisWeek;
  public $num_week;
  public function __construct()
  {
    $en_week=['one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen','twenty'];
    $stimestamp = strtotime("2021-3-1");
    $etimestamp = strtotime(date("Y-m-d", time()));
    $this->num_week = ceil((($etimestamp - $stimestamp) / 86400 + 1) / 7);
    $this->thisWeek = $en_week[$this->num_week-1];
  }

  public function getMostList()
  {

    $result = Db::table('week_score')->where('semester', input('get.semester'))->field("stuid,name,{$this->thisWeek}")->order("{$this->thisWeek}", 'ASC')->limit(0, 5)->select();
    $data = [
      'week'=>$this->thisWeek,
      'data'=>$result,
      'status'=>200
    ];
    return json($data, 200);
  }

  public function getPersonList()
  {
    $result = Db::table('week_score')->where('semester', input('get.semester'))->field("stuid,name,{$this->thisWeek}")->select();
    $data = [
      'week'=>$this->thisWeek,
      'data'=>$result,
      'status'=>200
    ];
    return json($data, 200);
  }

  public function getAllScList()
  {
    $result = Db::table('week_score')->where('semester', input('get.semester'))->field("stuid,name,allScore")->select();
    return json($result, 200);
  }

  public function getWeeklyList()
  {
    $result = Db::table('week_score')->where('semester', input('get.semester'))->withoutField("id,dormitory,stuid,name,semester,allScore")->select();
    return json($result, 200);
  }

  public function getItemList()
  {
    $result = Db::table('day')->where(['semester' => input('get.semester'), 'week' => $this->num_week])->field("quilt,dorm,classroom,meter,discipline,reward")->select();
    $midItemList = ['被子' => 0, '寝室' => 0, '课室' => 0, '仪表' => 0, '纪律' => 0, '+/-分' => 0];
    foreach ($result as $item) {
      if ($item['quilt']){
        $midItemList['被子']+=1;
      }
      if ($item['dorm']){
        $midItemList['寝室']+=1;
      }
      if ($item['classroom']){
        $midItemList['课室']+=1;
      }
      if ($item['meter']){
        $midItemList['仪表']+=1;
      }
      if ($item['discipline']){
        $midItemList['纪律']+=1;
      }
      if ($item['reward']){
        $midItemList['+/-分']+=1;
      }
    }
    $finalList = [];
    foreach ($midItemList as $item => $value) {
      $newArr['name'] = $item;
      $newArr['value'] = $value;
      array_push($finalList, $newArr);
    }
    return json($finalList,200);
  }

  public function getDormList()
  {
    $result = Db::table('week_score')->where('semester', input('get.semester'))->field("dormitory,{$this->thisWeek}")->select();
    $midList = ['7405' => 0, '7406' => 0, '7407' => 0, '7408' => 0, '6515' => 0];
    foreach ($result as $item) {
      if (array_key_exists($item['dormitory'], $midList)) {
        $midList[$item['dormitory']] += $item[$this->thisWeek];
      }
    }
    $finalList = [];
    foreach ($midList as $item => $value) {
      $newArr['name'] = $item;
      $newArr['value'] = $value;
      array_push($finalList, $newArr);
    }
    return json($finalList, 200);
  }
}
