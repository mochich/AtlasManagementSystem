@extends('layouts.sidebar')

@section('content')
<div class="vh-100 border">
<p>ユーザー検索</p>
<div class="search_content w-100  border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div>
        <span>ID : </span>{{ $user->id }}
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          {{ $user->over_name }}
          {{ $user->under_name }}
        </a>
      </div>
      <div>
        <span>カナ : </span>
        ({{ $user->over_name_kana }}
        {{ $user->under_name_kana }})
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span>男
        @elseif($user->sex == 2)
        <span>性別 : </span>女
        @else
        <span>性別 : </span>その他
        @endif
      </div>
      <div>
        <span>生年月日 : </span>{{ $user->birth_day }}
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span>教師(国語)
        @elseif($user->role == 2)
        <span>権限 : </span>教師(数学)
        @elseif($user->role == 3)
        <span>権限 : </span>講師(英語)
        @else
        <span>権限 : </span>生徒
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span>選択科目 :</span>
        @foreach($user->subjects as $subject)

        {{$subject->subject}}
        @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area w-25 border">
    <div class="">
      <div class="form_group">
        <p>検索</p>
        <label>
        <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">

        </label>
        <label><span class="small">カテゴリ</span>
        <select form="userSearchRequest" name="category">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
        </label>
        <label><span class="small">並び替え</span>
        <select name="updown" form="userSearchRequest">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
        </label>


        <p class="m-0 search_conditions"><span class="small accordion-title js-accordion-title">検索条件の追加</span></p>
      <div class="search_conditions_inner">
        <div class="form_group">
            <label class="selected_engineer"><span class="small">性別</span>
            <div>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
          </label>
            <label><span class="small">役職</span>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </label>

            <label class="selected_engineer"><span class="small">選択科目</span>
            <div class="">
            @foreach($subjects as $subject)

              <input type="checkbox" name="subject[]" value="{{ $subject->id }}" form="userSearchRequest">
              <label>{{ $subject->subject }}</label>

            @endforeach
            </div>
            </label>

        </div>
        </div>
        <div class="buttons">
          <input type="submit" name="search_btn" value="検索" form="userSearchRequest" >
          <input type="reset" value="リセット" form="userSearchRequest" class="reset">

          <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
        </div>

    </div>
  </div>
</div>
</div>
</div>
@endsection
