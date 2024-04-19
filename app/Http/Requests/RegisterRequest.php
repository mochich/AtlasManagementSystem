<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        //
        return [
            'over_name' => 'required|max:10|string',
            'under_name' => 'required|max:10|string',
            'over_name_kana' => 'required|max:30|string|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'under_name_kana' => 'required|max:30|string|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'mail_address' => 'required|max:100|email|unique:users,mail_address,',
            'password' => 'required|confirmed|max:20|min:8|alpha_num',
            'sex' => 'required|in:1,2,3',
            'birth_day' => 'date|date_format:Y-m-d|after:2000/1/1|before:today',
            'old_year' => 'required',
            'old_month' => 'required',
            'old_day' => 'required',
            'role' => 'required|in:1,2,3,4',
        ];
    }
    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!checkdate($this->input('old_month'), $this->input('old_day'), $this->input('old_year'))) {
                $validator->errors()->add('birthday_day', '正しい日付を入力してください');
            }
        });
    }

    public function attributes()
    {
        return [];
    }

    public function messages()
    {
        return [
            'over_name_kana.regex' => 'セイはカタカナで記入してください',
            'under_name_kana.regex' => 'メイはカタカナで記入してください'
        ];
    }
}


    // over_name	"・必須項目
    // ・文字列の型
    // ・10文字以下"
    // under_name	"・必須項目
    // ・文字列の型
    // ・10文字以下"
    // over_name_kana	"・必須項目
    // ・文字列の型
    // ・カタカナのみ
    // ・30文字以下"
    // under_name_kana	"・必須項目
    // ・文字列の型
    // ・カタカナのみ
    // ・30文字以下"
    // mail_address	"・必須項目
    // ・メールアドレスの形式
    // ・登録済みのもの無効
    // ・100文字以下"
    // sex	"・必須項目
    // ・男性、女性、その他以外無効"
    // old_year	"・必須項目
    // ・2000年1月1日から今日まで
    // ・正しい日付かどうか（例:2/31や6/31はNG）"
    // old_month
    // old_day
    // role	"・必須項目
    // ・講師(国語)、講師(数学)、教師(英語)、生徒以外無効"
    // password	"・必須項目
    // ・8文字以上30文字以下
    // ・確認用と同じかどうか"
