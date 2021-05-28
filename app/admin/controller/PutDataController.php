<?php


namespace app\admin\controller;

use think\facade\Db;

class PutDataController
{
  public $num_week;
  public $thisWeek;
  public $thisMonth;
  public $en_week;
  public $temData;
  public $semester;

  public function __construct()
  {
    $this->en_week = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen', 'twenty'];
    $stimestamp = strtotime("2021-3-1");
    $etimestamp = strtotime(date("Y-m-d", time()));
    $this->num_week = ceil((($etimestamp - $stimestamp) / 86400 + 1) / 7);
    $this->thisWeek = $this->en_week[$this->num_week - 1];
    $this->thisMonth = date('M');
    $this->temData = input('post.data');
    $this->semester = input('post.semester');
  }
  public function putInsertData()
  {
    $finalArr = [];
    foreach ($this->temData as $item) {
      $item['semester'] = $this->semester;
      $item['week'] = $this->num_week;
      array_push($finalArr, $item);
    }
    //    第一步，插入day表
    for ($i = 0; $i < count($finalArr); $i++) {
      $isLife = Db::table('day')->where(["semester" => $this->semester, "stuId" => $finalArr[$i]['stuId'], "dateTime" => $finalArr[$i]['dateTime']])->select();
      if (count($isLife) > 0) {
//      存在
        if ($finalArr[$i]['rRemarks']=='删除'){
          $dayResult = Db::table('day')->where(["semester" => $this->semester, "stuId" => $finalArr[$i]['stuId'], "dateTime" => $finalArr[$i]['dateTime']])->delete();
          $allResult = $this->existList($isLife);
          if ($dayResult > 0 && $allResult){
            return json('删除成功',200);
          }
        }
        $dayResult = Db::table('day')->where(["semester" => $this->semester, "stuId" => $finalArr[$i]['stuId'], "dateTime" => $finalArr[$i]['dateTime']])->data($finalArr[$i])->update();
        $allResult = $this->existList($isLife);
        $secResult = $this->notExist();
        if ($dayResult > 0 && $allResult && $secResult){
          return json('新增成功',200);
        }
      } else {
//      不存在
        if ($finalArr[$i]['rRemarks']=='删除'){
          return json('删除成功',200);
        }
        $dayResult = Db::name('day')->insert($finalArr[$i]);
        $allResult = $this->notExist();
        if ($dayResult > 0 && $allResult){
          return json('新增成功',200);
        }
      }
    }
  }
  public function notExist(){
    foreach ($this->temData as $temDatum) {
      $allScore = [$this->thisWeek => 0, $this->thisMonth => 0];
      if ($temDatum['quilt']) {
        $allScore[$this->thisWeek] += $temDatum['quilt'];
        $allScore[$this->thisMonth] += $temDatum['quilt'];
        //    第四步，插入part表
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('quilt')->update();
      }
      if ($temDatum['dorm']) {
        $allScore[$this->thisWeek] += $temDatum['dorm'];
        $allScore[$this->thisMonth] += $temDatum['dorm'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('dorm')->update();
      }
      if ($temDatum['classroom']) {
        $allScore[$this->thisWeek] += $temDatum['classroom'];
        $allScore[$this->thisMonth] += $temDatum['classroom'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('classroom')->update();
      }
      if ($temDatum['meter']) {
        $allScore[$this->thisWeek] += $temDatum['meter'];
        $allScore[$this->thisMonth] += $temDatum['meter'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('meter')->update();
      }
      if ($temDatum['discipline']) {
        $allScore[$this->thisWeek] += $temDatum['discipline'];
        $allScore[$this->thisMonth] += $temDatum['discipline'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('discipline')->update();
      }
      if ($temDatum['reward']) {
        $allScore[$this->thisWeek] += $temDatum['reward'];
        $allScore[$this->thisMonth] += $temDatum['reward'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->inc('reward')->update();
      }

      //    第二步，插入week_score表
      $weekResult = Db::table("week_score")->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->update(
        [
          "{$this->thisWeek}" => Db::raw("{$this->thisWeek} + {$allScore[$this->thisWeek]}"),
          "allScore" => Db::raw("allScore + {$allScore[$this->thisWeek]}")
        ]
      );

      //    第三步，插入month_score表
      $monthResult = Db::table("month_score")->where(["semester" => $this->semester, "stuid" => $temDatum['stuId']])->update([
        "{$this->thisMonth}" => Db::raw("{$this->thisMonth} + {$allScore[$this->thisMonth]}")
      ]);
      if ($weekResult && $monthResult > 0){
        return true;
      }
    }
  }
  public function existList($isLife){
    foreach ($isLife as $item) {
      $allScore = [$this->thisWeek => 0, $this->thisMonth => 0];
      if ($item['quilt']) {
        $allScore[$this->thisWeek] += $item['quilt'];
        $allScore[$this->thisMonth] += $item['quilt'];
        //    第四步，插入part表
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('quilt')->update();
      }
      if ($item['dorm']) {
        $allScore[$this->thisWeek] += $item['dorm'];
        $allScore[$this->thisMonth] += $item['dorm'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('dorm')->update();
      }
      if ($item['classroom']) {
        $allScore[$this->thisWeek] += $item['classroom'];
        $allScore[$this->thisMonth] += $item['classroom'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('classroom')->update();
      }
      if ($item['meter']) {
        $allScore[$this->thisWeek] += $item['meter'];
        $allScore[$this->thisMonth] += $item['meter'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('meter')->update();
      }
      if ($item['discipline']) {
        $allScore[$this->thisWeek] += $item['discipline'];
        $allScore[$this->thisMonth] += $item['discipline'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('discipline')->update();
      }
      if ($item['reward']) {
        $allScore[$this->thisWeek] += $item['reward'];
        $allScore[$this->thisMonth] += $item['reward'];
        Db::table('part')->where(["semester" => $this->semester, "stuid" => $item['stuId']])->dec('reward')->update();
      }
      //    第二步，插入week_score表
      $weekResult = Db::table("week_score")->where(["semester" => $this->semester, "stuid" => $item['stuId']])->update(
        [
          "{$this->thisWeek}" => Db::raw("{$this->thisWeek} - {$allScore[$this->thisWeek]}"),
          "allScore" => Db::raw("allScore - {$allScore[$this->thisWeek]}")
        ]
      );

      //    第三步，插入month_score表
      $monthResult = Db::table("month_score")->where(["semester" => $this->semester, "stuid" => $item['stuId']])->update([
        "{$this->thisMonth}" => Db::raw("{$this->thisMonth} - {$allScore[$this->thisMonth]}")
      ]);

      if ($weekResult && $monthResult > 0){
        return true;
      }
    }

  }
}