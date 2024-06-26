<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  // public function prev()
  // {
  //   $dt =
  //     Carbon::today();
  //   return $dt->subMonth();
  // }
  // public function next()
  // {
  //   $dt =
  //     Carbon::today();
  //   return $dt->addMonth();
  // }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table ">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");
        // 過去日
        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class=" border calendar-td ' . $day->pastClassName() .' ' . $day->getClassName() .'">';
        }
        // 今日以降
        else {
          $html[] = '<td class="border calendar-td ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();

        // 予約されている日の表示
        if (in_array($day->everyDay(), $day->authReserveDay())) {
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;

          // 予約済みの表示
          if ($reservePart == 1) {
            $reservePart = "リモ1部";
          } else if ($reservePart == 2) {
            $reservePart = "リモ2部";
          } else if ($reservePart == 3) {
            $reservePart = "リモ3部";
          }

          // 過去日
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color:#000;">' . $reservePart . '参加</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
          // 予約削除ボタン
          else {
            $html[] = '<button type="submit" class="btn btn-danger edit-modal-open p-0 w-75" name="delete_date" style="font-size:12px" day="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '" part="' . $reservePart . '" >' . $reservePart .

              '</button>';




            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
        }
        // 予約してない日の表示
        else {
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color:#000;">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {

            $html[] = $day->selectPart($day->everyDay());
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
