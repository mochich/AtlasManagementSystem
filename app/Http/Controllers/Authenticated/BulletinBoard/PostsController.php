<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
use DB;


class PostsController extends Controller
{

    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments', 'subCategories')->get();
        $categories = MainCategory::get();
        $sub_categories = SubCategory::get();
        $like = new Like;
        $post_comment = new Post;

        if (!empty($request->keyword)) {
            $sub_category = $request->keyword;
            $sub_category_id = SubCategory::where('sub_category', $sub_category)->get('id');
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('post_title', 'like', '%' . $request->keyword . '%')
                ->orWhere('post', 'like', '%' . $request->keyword . '%')->orwhereHas('subCategories', function ($q) use ($sub_category_id) {
                $q->whereIn('post_sub_categories.sub_category_id', $sub_category_id);
            })->get();

        } else if ($request->category_word) {
            $sub_category = $request->input('category_word');

            //クリックしたサブカテゴリーのIDを取得
            $sub_category_id = SubCategory::where('sub_category', $sub_category)->get('id');
            // 上のIDがsub_category_idになってるpostのIDを取得
            $posts = Post::whereHas('subCategories', function ($q) use ($sub_category_id) {
                $q->whereIn('post_sub_categories.sub_category_id', $sub_category_id);
            })->get();
        } else if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->whereIn('id', $likes)->get();
        } else if ($request->my_posts) {
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment', 'sub_categories'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::get();
        $sub_categories = SubCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories', 'sub_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $sub_category
            = $request->post_subcategory;
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        $post_category = Post::findOrFail($post->id);
        $post_category->subCategories()->attach($sub_category);
        DB::commit();

        return redirect()->route('post.show');
    }

    public function postEdit(PostFormRequest $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request)
    {
        $validated = $request->validate([
            'main_category_name' => 'required|unique:main_categories,main_category|string|max:100'
        ]);

        // "・必須項目
        // ・100文字以内
        // ・文字列型
        // ・同じ名前のメインカテゴリーは登録できない"

        $main_category_name = $request->input('main_category_name');
        MainCategory::create(['main_category' => $main_category_name]);
        return redirect()->route('post.input');
    }
    // サブカテゴリー作成
    public function subCategoryCreate(Request $request)
    {
        $validated = $request->validate([
            'main_category' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|unique:sub_categories,sub_category|string|max:100'
        ]);
        $main_category_id = $request->input('main_category');
        $sub_category_name = $request->input('sub_category_name');

        SubCategory::create([
            'sub_category' => $sub_category_name,
            'main_category_id' => $main_category_id
        ]);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request)
    {
        $request->validate([
            'comment' => 'required|max:2500|string'
        ]);


        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
