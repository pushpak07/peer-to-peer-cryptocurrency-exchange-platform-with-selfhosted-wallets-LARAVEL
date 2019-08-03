<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use App\Models\Role;
use App\Models\Trade;
use App\Models\User;
use App\Notifications\Verification\PhoneVerification;
use App\Traits\ThrottlesEmails;
use App\Traits\ThrottlesSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
	use ThrottlesEmails, ThrottlesSms;

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
	 * Settings Index Page
	 *
	 * @param User $user
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
	 */
	public function index(User $user)
	{
		$setting = $user->getSetting();

		$notification_settings = $user->getNotificationSettings();

		$profile = $user->getProfile();

		$qr_code = $this->getQRCodeUrl($user, true);

		return view('profile.settings.index')
			->with(compact('user', 'profile'))
			->with(compact('notification_settings'))
			->with(compact('qr_code', 'setting'));
	}

	/**
	 * Save user profile
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function uploadPicture(Request $request, User $user)
	{
		if ($request->hasFile('image')) {
			try {
				$avatar = $request->file('image');

				$name = 'avatar.' . $avatar->getClientOriginalExtension();

				$link = route('profile.picture', [
					'user' => $user->name,
					'name' => $name
				], false);

				$path = getAvatarPath($user, $name);
				File::makeDirectory(dirname($path), 0755, true, true);

				Image::make($avatar)->resize(300, 300)->save($path);

				$profile = $user->getProfile();
				$profile->picture = $link;

				$user->profile()->save($profile);

				userActivity()->log('Changed picture');

				return response(__("Your photo has been uploaded successfully!"));
			} catch (\Exception $e) {
				return response($e->getMessage(), 404);
			}
		} else {
			return response(__('Something went wrong! Try again later.'), 400);
		}
	}


	/**
	 * Delete user profile picture
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function deletePicture(Request $request, User $user)
	{
		if ($request->ajax()) {
			$profile = $user->getProfile();

			$profile->picture = null;

			$user->profile()->save($profile);

			userActivity()->log('Removed picture');

			return response(__("The operation was successful!"));
		} else {
			return abort(403);
		}
	}

	/**
	 * Update verification details
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function updateVerification(Request $request, User $user)
	{
		$this->validate($request, [
			'phone'         => 'nullable|phone',
			'phone_country' => 'required_with:phone',
			'email'         => 'nullable|email',
		]);

		if ($request->email && ($request->email != $user->email)) {
			$this->validate($request, [
				'email'            => 'unique:users',
				'current_password' => 'required'
			]);

			if (!Hash::check($request->current_password, $user->password)) {
				$message = __("The password entered was incorrect!");

				return error_response($message);
			}

			$user->fill([
				'email'    => $request->email,
				'verified' => false
			]);

			if (!$this->hasTooManyEmailAttempts($user)) {
				resolve('Lunaweb\EmailVerification\EmailVerification')
					->sendVerifyLink($user);

				$this->incrementEmailAttempts($user);
			}
		}

		if ($request->phone && ($request->phone != $user->phone)) {
			$this->validate($request, [
				'phone'            => 'unique:users',
				'current_password' => 'required'
			]);

			if (!Hash::check($request->current_password, $user->password)) {
				$message = __("The password entered was incorrect!");

				return error_response($message);
			}

			$user->fill([
				'phone'          => $request->phone,
				'verified_phone' => false
			]);

			if (!$this->hasTooManySmsAttempts($user)) {
				$user->notify(new PhoneVerification());

				$this->incrementSmsAttempts($user);
			}
		}

		$message = __("Saved! Refresh page to proceed with verification.");

		return success_response($message);
	}

	/**
	 * Update profile
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function updateProfile(Request $request, User $user)
	{
		$this->validate($request, [
			'first_name' => 'nullable|string',
			'last_name'  => 'nullable|string',
			'bio'        => 'nullable|string',
		]);

		$profile = $user->getProfile();

		$profile_data = $request->only([
			'bio', 'first_name', 'last_name'
		]);

		$profile->fill($profile_data);
		$user->profile()->save($profile);

		userActivity()->log('Updated profile');

		$message = __("Your profile has been updated!");

		return success_response($message);
	}

	/**
	 * Update preferences
	 *
	 * @param Request $request
	 * @param User $user
	 */
	public function updatePreferences(Request $request, User $user)
	{
		$timezones = get_php_timezones();
		$currencies = get_iso_currencies();

		$this->validate($request, [
			'currency' => [
				'nullable',
				Rule::in(array_keys($currencies))
			],
			'timezone' => [
				'nullable',
				Rule::in(array_keys($timezones))
			],
		]);

		$user_data = $request->only([
			'timezone', 'currency'
		]);

		foreach ($request->notification as $name => $set) {
			$settings = $user->notification_setting()
				->where('name', $name)->first();

			if ($settings->sms !== null) {
				$settings->sms = isset($set['sms']);
			}

			if ($settings->database !== null) {
				$settings->database = isset($set['database']);
			}

			if ($settings->email !== null) {
				$settings->email = isset($set['email']);
			}

			$settings->save();
		}

		$user->fill($user_data)->save();

		userActivity()->log('Updated preference');

		$message = __("Your preference has been updated!");

		return success_response($message);
	}

	/**
	 * Update user setting
	 *
	 * @param Request $request
	 * @param $user
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function updateSettings(Request $request, User $user)
	{
		$this->validate($request, [
			'user_login_2fa'        => 'required',
			'outgoing_transfer_2fa' => 'required',
			'current_password'      => 'required'
		]);

		if (!Hash::check($request->current_password, $user->password)) {
			$message = __("The password entered was incorrect!");

			return error_response($message);
		}

		$setting = $user->getSetting();
		$setting->user_login_2fa = $request->user_login_2fa;
		$setting->outgoing_transfer_2fa = $request->outgoing_transfer_2fa;
		$setting->save();

		userActivity()->log('Updated settings');

		$message = __("Your profile has been updated!");

		return success_response($message);
	}

	/**
	 * Update user password
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function updatePassword(Request $request, User $user)
	{
		$this->validate($request, [
			'current_password' => 'required',
			'password'         => 'required|min:6|confirmed',
		]);

		if (!Hash::check($request->current_password, $user->password)) {
			$message = __("The password entered was incorrect!");

			return error_response($message);
		}

		$user->fill([
			'password' => bcrypt($request->password)
		]);

		$user->save();

		userActivity()->log('Changed password');

		$message = __("Your password has been updated!");

		return success_response($message);
	}

	/**
	 * Delete user account
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function deleteAccount(Request $request, User $user)
	{
		$this->validate($request, [
			'current_password' => 'required'
		]);

		try {
			if (!Hash::check($request->current_password, $user->password)) {
				$message = __("The password entered was incorrect!");

				return error_response($message);
			}

			$trades = Trade::whereIn('status', ['active', 'dispute'])
				->where(function ($query) use ($user) {
					$query->where('partner_id', $user->id);
					$query->orWhere('user_id', $user->id);
				});

			if ($trades->count()) {
				$message = __('You cannot do this right now!');

				return error_response($message);
			}

			$user->delete();

			userActivity()->log('Deleted account');

			$message = __("Your account has been queued for delete!");

			return success_response($message);

		} catch (\Exception $e) {
			return error_response($e->getMessage());
		}
	}

	/**
	 * Update user role
	 *
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function updateRole(Request $request, User $user)
	{
		$this->validate($request, [
			'role'   => 'required|array|size:1',
			'role.*' => Rule::in(Role::all()->pluck('name'))
		]);

		$user->syncRoles($request->role);

		$user->save();

		$message = __("User role has been updated!");

		return success_response($message);
	}

	/**
	 * @param User $user
	 * @param bool $insecure
	 * @return string
	 * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
	 */
	public function getQRCodeUrl($user, $insecure = true)
	{
		$this->google2fa->setAllowInsecureCallToGoogleApis($insecure);

		return $this->google2fa->getQRCodeGoogleUrl(
			config('app.name'), $user->email, $user->google2fa_secret
		);
	}

	public function moderationActivityData(Request $request, User $user)
	{
		if ($request->ajax()) {
			return DataTables::of($user->moderation_activities())
				->editColumn('moderator', function ($data) {
					$moderator = $data->moderator;

					if (User::where('name', $data->moderator)->first()) {
						$link = route('profile.index', [
							'user' => $data->moderator
						]);

						$moderator = \HTML::link($link, $data->moderator);
					}

					return $moderator;
				})
				->editColumn('created_at', function ($data) {
					if ($date = Carbon::parse($data->created_at)) {

						return $date->timezone(Auth::user()->timezone)
							->toFormattedDateString();

					}
				})
				->editColumn('link', function ($data) {
					if ($data->link) {
						$html = "<button type='button' class='btn btn-icon round btn-secondary'>";
						$html .= "<i class='la la-link'></i>";
						$html .= "</button>";

						return \HTML::link($data->link, $html);
					}
				})
				->rawColumns(['moderator', 'link'])
				->make(true);
		} else {
			return abort(403);
		}
	}
}
