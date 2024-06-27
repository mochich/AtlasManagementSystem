@extends('layouts.sidebar')
@section('content')
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="border w-75 m-auto pt-5 pb-5 white" style="box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.35);">

  <p class="text-center">{{ $calendar->getTitle() }}</p>
    {!! $calendar->render() !!}
    <div class="adjust-table-btn m-auto text-right">
      <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
    </div>

  </div>
</div>
@endsection
