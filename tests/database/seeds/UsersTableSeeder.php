<?php

use Jsdecena\Baserepo\Models\User;

class UsersTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        factory(User::class)->create();
    }
}