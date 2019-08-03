<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * An instance of Google 2fa api
     *
     * @var Google2FA
     */
    protected $google2fa;

    /**
     * UsersTableSeeder constructor.
     *
     * @param Google2FA $google2fa
     */
    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::count()) {

            // Create Admin Account
            $user = User::create([
                'name' => 'admin',
                'google2fa_secret' => $this->google2fa->generateSecretKey(),
                'email' => 'admin@mail.com',
                'password' => bcrypt(123456)
            ]);

            $user->save();
            $user->assignRole('admin');

            // Create Moderator Account
            $user = User::create([
                'name' => 'moderator',
                'google2fa_secret' => $this->google2fa->generateSecretKey(),
                'email' => 'moderator@mail.com',
                'password' => bcrypt(123456)
            ]);

            $user->save();
            $user->assignRole('moderator');

            // Create User Account
            $user = User::create([
                'name' => 'user',
                'google2fa_secret' => $this->google2fa->generateSecretKey(),
                'password' => bcrypt(123456),
                'email' => 'user@mail.com',
            ]);

            $user->save();
            $user->assignRole('user');
        }
    }

}
