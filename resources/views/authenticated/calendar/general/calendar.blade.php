@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5 white" style="box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.35);">
    <p class="text-center">{{ $calendar->getTitle() }}</p>
    <div class="w-75 m-auto border" style="">
        {!! $calendar->render() !!}
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
</div>
  </div>
</div>
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('deleteParts') }}" method="post">
      <div class="w-100">
        <div class="modal-inner-day w-50 m-auto">
          <input type="hidden" name="day" class="w-100">
          <p>予約日：<span></span></p>
        </div>
        <div class="modal-inner-part w-50 m-auto ">
          <input type="hidden" name="part" class="w-100"></input>
          <p>時間：<span></span></p>
        </div>
        <p class="w-50 m-auto">上記の予約をキャンセルしてもよろしいですか？</p>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-primary d-inline-block" href="">閉じる</a>

          <input type="submit" class="btn btn-danger d-block" value="キャンセル">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>

@endsection
