<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.07.16
 * Time: 12:30
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteExpiredTokens extends Command
{
    protected $signature = 'tokens:delete';

    protected $description = 'Delete expired User tokens';

    public function handle()
    {
        /** @var User[] $users */
        $time = time();
        $users = User::where('accessTokens.expires', '<=', $time)
            ->orWhere('refreshTokens.expires', '<=', $time)
            ->orWhere('tokens.reset_password.expired', '<=', $time)
            ->orWhere('tokens.verify_email.expired', '<=', $time)
            ->get();

        $filter = function ($item) use ($time) {
            return $item->expires >= $time;
        };

        foreach ($users as $user) {
            $filteredAccessTokens = $user->accessTokens()->reject($filter);
            if ($filteredAccessTokens->count() > 0) {
                $user->accessTokens()->dissociate($filteredAccessTokens);
            }

            $filteredRefreshTokens = $user->refreshTokens()->reject($filter);
            if ($filteredRefreshTokens->count() > 0) {
                $user->refreshTokens()->dissociate($filteredRefreshTokens);
            }

            if ($user->getResetPasswordToken()['expired'] < $time) {
                $user->removeResetPasswordToken();
            }
            if ($user->getVerifyEmailToken()['expired'] < $time) {
                $user->removeVerifyEmailToken();
            }
            $user->save();
        }
    }
}
