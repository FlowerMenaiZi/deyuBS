<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});
Route::rule('checkLogin','app\admin\controller\LoginController@checkLogin','POST');
Route::rule('checkToken','app\admin\controller\CheckTokenController@checkToken','GET');
Route::rule('getUser','app\admin\controller\GetUserController@getUser','GET');
Route::rule('putInsertData','app\admin\controller\PutDataController@putInsertData','POST');
Route::rule('getDayList','app\home\controller\DayController@getDayList','GET');
Route::rule('getPartList','app\home\controller\PartController@getPartList','GET');
Route::rule('getMostList','app\home\controller\WeekController@getMostList','GET');
Route::rule('getPersonList','app\home\controller\WeekController@getPersonList','GET');
Route::rule('getAllScList','app\home\controller\WeekController@getAllScList','GET');
Route::rule('getWeeklyList','app\home\controller\WeekController@getWeeklyList','GET');
Route::rule('getItemList','app\home\controller\WeekController@getItemList','GET');
Route::rule('getDormList','app\home\controller\WeekController@getDormList','GET');
Route::rule('getMonthMostList','app\home\controller\MonthController@getMonthMostList','GET');
Route::rule('getMonthlyList','app\home\controller\MonthController@getMonthlyList','GET');
Route::rule('getMonthDormList','app\home\controller\MonthController@getMonthDormList','GET');
Route::rule('getMonthItemList','app\home\controller\MonthController@getMonthItemList','GET');