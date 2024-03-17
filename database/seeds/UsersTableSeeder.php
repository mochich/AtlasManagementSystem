<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'over_name' => 'テスト',
                'under_name' => 'ユーザー',
                'over_name_kana' => 'テスト',
                'under_name_kana' => 'ユーザー',
                'mail_address' => 'test@atlas.com',
                'sex' => '1',
                'birth_day' => '2000-01-01',
                'role' => '1',
                'password' => bcrypt('password')
            ]
        );
    }
}
