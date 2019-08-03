<?php

namespace App\Http\Controllers\Admin\Platform;

use App\Models\User;
use Barryvdh\TranslationManager\Manager;
use Barryvdh\TranslationManager\Models\Translation;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jackiedo\DotenvEditor\DotenvEditor;

class TranslationController extends Controller
{
	/**
	 * @var DotenvEditor
	 */
	protected $editor;

	/**
	 * @var Manager
	 */
	protected $manager;

	/**
	 * GeneralController constructor.
	 *
	 * @param Manager $manager
	 * @param DotenvEditor $editor
	 * @throws \Exception
	 */
	public function __construct(DotenvEditor $editor, Manager $manager)
	{
		$this->editor = $editor;
		$this->manager = $manager;
	}

	/**
	 * @param string $group
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$groups = Translation::groupBy('group');

		$supportedLocales = config('laravellocalization.supportedLocales');
		$locales = collect($supportedLocales)->only($this->manager->getLocales());

		if ($excludedGroups = $this->manager->getConfig('exclude_groups')) {
			$groups->whereNotIn('group', $excludedGroups);
		}

		$groups = $groups->select('group')->orderBy('group')->get()->pluck('group', 'group');

		return view('admin.platform.translation.index')
			->with(compact('locales'))
			->with(compact('groups'));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function export()
	{
		$this->manager->exportTranslations('*');

		$message = __('Language files has been updated!');

		return success_response($message);
	}

	/**
	 * @param null $group
	 * @return mixed
	 */
	public function groupEdit($group = null)
	{
		$locales = $this->manager->getLocales();
		$total = Translation::where('group', $group)->count();

		if ($total > 0) {
			$totalChanged = Translation::where('group', $group)
				->where('status', Translation::STATUS_CHANGED)
				->count();

			return view('admin.platform.translation.edit')
				->with(compact('totalChanged'))
				->with(compact('total', 'group'))
				->with(compact('locales'));
		}

		return abort(404);
	}

	/**
	 * @param Request $request
	 * @param null $group
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function groupUpdate(Request $request, $group = null)
	{
		$this->validate($request, [
			'locale' => 'required',
			'key'    => 'required',
			'value'  => 'required'
		]);

		if (in_array($group, $this->manager->getConfig('exclude_groups'))) {
			return error_response(__('You are not allowed to edit this group!'));
		}

		$translation = Translation::firstOrNew([
			'group'  => $group,
			'locale' => $request->get('locale'),
			'key'    => $request->get('key'),
		]);

		$translation->status = Translation::STATUS_CHANGED;
		$translation->value = (string) $request->get('value') ?: null;
		$translation->save();

		return success_response(__('Record was updated!'));
	}

	/**
	 * @param null $group
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function groupExport($group = null)
	{
		$json = ($group === '_json');
		$this->manager->exportTranslations($group, $json);

		$message = __('Language files has been updated!');

		return success_response($message);
	}

	/**
	 * @param Request $request
	 * @param null $group
	 * @return LengthAwarePaginator|void
	 */
	public function groupData(Request $request, $group = null)
	{
		if ($request->ajax()) {
			$page = $request->page ?: 0;

			$records = Translation::where('group', $group)
				->orderBy('key', 'asc')
				->paginate(1000, ['*'], 'page', $page);

			$translations = [];

			foreach ($records->items() as $data) {
				$translations[$data->key][$data->locale] = $data;
			}

			$items = array_chunk($translations, 1, true);

			return new LengthAwarePaginator(
				$items,
				$records->total(),
				$records->perPage(),
				$records->currentPage(), [
					'path'  => request()->url(),
					'query' => [
						'page' => $records->currentPage()
					]
				]
			);
		} else {
			return abort(403);
		}
	}

	/**
	 * Update general configurations
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request)
	{
		$environment = collect($this->environment())
			->intersectByKeys($request->all());

		$rules = $environment->mapWithKeys(function ($value, $key) {
			return [$key => $value['rules']];
		});

		$this->validate($request, $rules->toArray());

		$values = $environment->map(function ($value, $key) use ($request) {
			$data = [
				'key'   => $key,
				'value' => $request->get($key)
			];

			if (isset($value['save'])) {
				$data = [
					'key'   => $key,
					'value' => $value['save']($request)
				];
			}

			return $data;
		});

		$this->editor->setKeys($values->toArray());
		$this->editor->save();

		$message = __("Your settings has been updated!");

		return success_response($message);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function importTranslation(Request $request)
	{
		$type = $request->get('type', false);

		$total = $this->manager->importTranslations($type);

		$message = __(':total language keys available.', [
			'total' => $total
		]);

		return success_response($message);
	}

	public function findTranslation()
	{
		collect([
			base_path('resources/views'),
			base_path('app'),
			base_path('packages'),
		])->each(function ($path) use (&$total) {
			$total += $this->manager->findTranslations($path);
		});

		$message = __(':total new keys was found.', [
			'total' => $total
		]);

		return success_response($message);
	}

	public function addLocale(Request $request)
	{
		$locale = $request->get('locale');

		$supportedLocales = config('laravellocalization.supportedLocales');

		$this->validate($request, [
			'locale' => ['required', Rule::in(array_keys($supportedLocales))]
		]);

		$locales = $this->manager->getLocales();

		if (in_array($locale, $locales)) {
			return error_response(__('The locale specified already exists.'));
		}
		$this->manager->addLocale($locale);

		return success_response(__('Locale was added successfully.'));
	}

	public function removeLocale(Request $request)
	{
		$locale = $request->get('locale');

		if ($locale == 'en') {
			$message = __('You cannot remove this locale.');

			return error_response($message);
		}
		$this->manager->removeLocale($locale);

		$message = __('Locale :locale was successfully removed.', [
			'locale' => $locale
		]);

		return success_response($message);
	}

	/**
	 * Define environment properties
	 *
	 * @return array
	 */
	private function environment()
	{
		return [
			'APP_LOCALE' => [
				'rules' => [
					'required', Rule::in(array_keys(getAvailableLocales()))
				],
			],
		];
	}
}
