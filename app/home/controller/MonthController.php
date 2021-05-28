<?php


namespace app\home\controller;

use think\facade\Db;

class MonthController
{
  public $thisMonth;

  public function __construct()
  {
    $this->thisMonth = date('M');
//    $this->thisMonth = 'Apr';
  }

  public function getMonthMostList()
  {
    $result = Db::table('month_score')->where('semester', input('get.semester'))->field("stuid,name,{$this->thisMonth}")->order("{$this->thisMonth}", 'ASC')->limit(0, 5)->select();
    $data = [
      'month' => $this->thisMonth,
      'data' => $result,
      'status' => 200
    ];
    return json($data, 200);
  }

  public function getMonthlyList()
  {
    $result = Db::table('month_score')->where('semester', input('get.semester'))->withoutField("id,dormitory,stuid,name,semester")->select();
    return json($result, 200);
  }

  public function getMonthDormList()
  {
    $result = Db::table('month_score')->where('semester', input('get.semester'))->field("dormitory,{$this->thisMonth}")->select();
    $MonthMidList = ['7405' => 0, '7406' => 0, '7407' => 0, '7408' => 0, '6515' => 0];
    foreach ($result as $item) {
      if (array_key_exists($item['dormitory'], $MonthMidList)) {
        $MonthMidList[$item['dormitory']] += $item[$this->thisMonth];
      }
    }
    $finalList = [];
    foreach ($MonthMidList as $item => $value) {
      $newArr['name'] = $item;
      $newArr['value'] = $value;
      array_push($finalList, $newArr);
    }
    return json($finalList, 200);
  }

  public function getMonthItemList()
  {
    $month=date('Y-m');
//    $month = '2021-04';
    $startMonth = $month . '-01';
    $endMonth = $month . '-31';
    $result = Db::table('day')->where('semester', input('get.semester'))->whereBetween('dateTime', [$startMonth, $endMonth])->field("quilt,dorm,classroom,meter,discipline,reward")->select();
    $midMonthItemList = ['被子' => 0, '寝室' => 0, '课室' => 0, '仪表' => 0, '纪律' => 0, '+/-分' => 0];
    foreach ($result as $item) {
      if ($item['quilt']) {
        $midMonthItemList['被子'] += 1;
      }
      if ($item['dorm']) {
        $midMonthItemList['寝室'] += 1;
      }
      if ($item['classroom']) {
        $midMonthItemList['课室'] += 1;
      }
      if ($item['meter']) {
        $midMonthItemList['仪表'] += 1;
      }
      if ($item['discipline']) {
        $midMonthItemList['纪律'] += 1;
      }
      if ($item['reward']) {
        $midMonthItemList['+/-分'] += 1;
      }
    }
    $finalList = [];
    foreach ($midMonthItemList as $item => $value) {
      $newArr['name'] = $item;
      $newArr['value'] = $value;
      array_push($finalList, $newArr);
    }
    return json($finalList, 200);
  }

}