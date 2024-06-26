@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <!-- <p class="w-75 m-auto">投稿一覧</p> -->
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p class="contributor"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p class="post_title"><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        @foreach($post->subCategories as $subCategory)

        <div class="category_btn">{{$subCategory->sub_category}}</div>
@endforeach
        <div class="d-flex post_status">
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="posts_counts{{ $post->id }}">{{$post_comment->commentCounts($post->id)}}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{$like->likeCounts($post->id)}}</span></p>

            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{$like->likeCounts($post->id)}}</span></p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area border w-25">
    <ul class="menu">
      <li>
      <!-- <input type="button" onclick="{{route('post.input') }}" value="投稿" class="button"> -->
       <a href="{{route('post.input') }}" class="button">投稿</a>
      </li>
      <li class="search_box">

        <input type="text" class="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest"><input type="submit" value="検索" form="postSearchRequest" class="submit">

      </li>
     <li>
      <input type="submit" name="like_posts" class="menu_button  " value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="menu_button  " value="自分の投稿" form="postSearchRequest">
      </li>
      </ul>
      <p>カテゴリー検索</p>
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}">
          <p class="accordion-title js-accordion-title">{{ $category->main_category }}</p>
          <div class="accordion-content">
              @foreach($sub_categories as $sub_category)
              <ul>
                @if($sub_category->main_category_id==$category->id)
                <li><input type="submit" name="category_word" class="category_btn" value="{{$sub_category->sub_category}}" form="postSearchRequest"></li>
                @endif
              </ul>
              @endforeach
              </div>
        </li>
        @endforeach
      </ul>

  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
