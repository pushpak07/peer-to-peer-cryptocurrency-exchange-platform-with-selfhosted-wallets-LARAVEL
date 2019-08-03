<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait ThrottlesSms
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param User $user
     * @return bool
     */
    protected function hasTooManySmsAttempts($user)
    {
        return $this->smsLimiter()->tooManyAttempts(
            $this->throttleSmsKey($user), $this->maxSmsAttempts(), $this->decaySmsMinutes()
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  User $user
     * @return void
     */
    protected function incrementSmsAttempts($user)
    {
        $this->smsLimiter()->hit(
            $this->throttleSmsKey($user), $this->decaySmsMinutes()
        );
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param User $user
     * @return void
     */
    protected function clearSmsAttempts($user)
    {
        $this->smsLimiter()->clear($this->throttleSmsKey($user));
    }

    /**
     * Show the number of minutes left to retry
     *
     * @return int
     */
    public function retrySmsAttemptInMinutes($user)
    {
        $seconds = $this->smsLimiter()->availableIn(
            $this->throttleSmsKey($user)
        );

        return round($seconds / 60) ?: 0;
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param User $user
     * @return string
     */
    private function throttleSmsKey($user)
    {
        return Str::lower($user->name) . '|' . 'sms';
    }

    /**
     * Get the rate smsLimiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    private function smsLimiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    private function maxSmsAttempts()
    {
        return property_exists($this, 'maxSmsAttempts') ? $this->maxSmsAttempts : 5;
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    private function decaySmsMinutes()
    {
        return property_exists($this, 'decaySmsMinutes') ? $this->decaySmsMinutes : 60;
    }
}
