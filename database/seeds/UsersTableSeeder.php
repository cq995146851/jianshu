<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->times(10)->create();
        $user = User::find(1);
        $user->name="陈骞";
        $user->email="995146851@qq.com";
        $user->activated = true;
        $user->save();
    }
}
