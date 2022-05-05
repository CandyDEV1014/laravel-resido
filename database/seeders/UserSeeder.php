<?php

namespace Database\Seeders;

use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\ActivationInterface;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = $this->uploadFiles('users');

        Schema::disableForeignKeyConstraints();

        User::truncate();

        $user = new User;
        $user->first_name = 'System';
        $user->last_name = 'Admin';
        $user->email = 'admin@thesky9.com';
        $user->username = 'admin';
        $user->password = bcrypt('12345678');
        $user->super_user = 1;
        $user->manage_supers = 1;
        $user->avatar_id = $files[0]['data']->id;
        $user->save();

        event('acl.activating', $user);

        $activationRepository = app(ActivationInterface::class);

        $activation = $activationRepository->createUser($user);

        event('acl.activated', [$user, $activation]);

        return $activationRepository->complete($user, $activation->code);
    }
}
