<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait ThrottlesEmails
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param User $user
     * @return bool
     */
    protected function hasTooManyEmailAttempts($user)
    {
        return $this->emailLimiter()->tooManyAttempts(
            $this->throttleEmailKey($user), $this->maxEmailAttempts(), $this->decayEmailMinutes()
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  User $user
     * @return void
     */
    protected function incrementEmailAttempts($user)
    {
        $this->emailLimiter()->hit(
            $this->throttleEmailKey($user), $this->decayEmailMinutes()
        );
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param User $user
     * @return void
     */
    protected function clearEmailAttempts($user)
    {
        $this->emailLimiter()->clear($this->throttleEmailKey($user));
    }

    /**
     * Show the number of minutes left to retry
     *
     * @return int
     */
    public function retryEmailAttemptInMinutes($user)
    {
        $seconds = $this->emailLimiter()->availableIn(
            $this->throttleEmailKey($user)
        );

        return round($seconds / 60) ?: 0;
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param User $user
     * @return string
     */
    protected function throttleEmailKey($user)
    {
        return Str::lower($user->name) . '|' . 'email';
    }

    /**
     * Get the rate emailLimiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function emailLimiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    public function maxEmailAttempts()
    {
        return property_exists($this, 'maxEmailAttempts') ? $this->maxEmailAttempts : 5;
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayEmailMinutes()
    {
        return property_exists($this, 'decayEmailMinutes') ? $this->decayEmailMinutes : 30;
    }
}
