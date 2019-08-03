<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\PaymentMethod;
use App\Models\PaymentMethodCategory;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = PaymentMethodCategory::all();

        $categories = $categories->pluck('name', 'id');

        return view('admin.settings.offer.index')
            ->with(compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeOfferTag(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $values = $request->only(['id', 'name']);

        if ($request->has('id')) {
            Tag::find($request->id)->update($values);
        } else {
            Tag::create($values);
        }

        $message = __('Tag has been updated!');

        return success_response($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function offerTagsData(Request $request)
    {
        if ($request->ajax()) {
            $categories = Tag::query();

            return DataTables::eloquent($categories)
                ->addColumn('action', function ($data) {
                    return view('admin.settings.offer.partials.datatable.offer_tag_action')
                        ->with(compact('data'));
                })
                ->make(true);
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteOfferTag(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if ($category = Tag::find($request->id)) {
            try {
                $message = __('Tag has been deleted!');

                $category->delete();

                return success_response($message);
            } catch (\Exception $e) {
                return error_response($e->getMessage());
            }
        } else {
            $message = __('Selected tag could not be found!');

            return error_response($message);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storePaymentCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $values = $request->only(['id', 'name']);

        if ($request->has('id')) {
            PaymentMethodCategory::find($request->id)->update($values);
        } else {
            PaymentMethodCategory::create($values);
        }

        $message = __('Category has been updated!');


        return success_response($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function paymentCategoriesData(Request $request)
    {
        if ($request->ajax()) {
            $categories = PaymentMethodCategory::query();

            return DataTables::eloquent($categories)
                ->addColumn('action', function ($data) {
                    return view('admin.settings.offer.partials.datatable.payment_category_action')
                        ->with(compact('data'));
                })
                ->make(true);
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deletePaymentCategory(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if ($category = PaymentMethodCategory::find($request->id)) {
            try {
                $category->delete();

                $message = __('Category has been deleted!');

                return success_response($message);
            } catch (\Exception $e) {
                return error_response($e->getMessage());
            }
        } else {
            $message = __('Selected category could not be found!');

            return error_response($message);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storePaymentMethod(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required',
            'category'   => 'required|exists:payment_method_categories,id',
            'time_frame' => 'required|numeric'
        ]);

        $message = __('Method has been updated!');

        if ($request->has('id')) {
            PaymentMethod::find($request->id)
                ->update([
                    'name'                       => $request->name,
                    'payment_method_category_id' => $request->category,
                    'time_frame'                 => $request->time_frame,
                ]);
        } else {
            PaymentMethod::create([
                'name'                       => $request->name,
                'payment_method_category_id' => $request->category,
                'time_frame'                 => $request->time_frame,
            ]);
        }

        return success_response($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deletePaymentMethod(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if ($category = PaymentMethod::find($request->id)) {
            try {
                $category->delete();

                $message = __('Method has been deleted!');

                return success_response($message);
            } catch (\Exception $e) {
                return error_response($e->getMessage());
            }
        } else {
            $message = __('Selected method could not be found!');

            return error_response($message);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function paymentMethodsData(Request $request)
    {
        if ($request->ajax()) {
            $methods = PaymentMethod::query();

            return DataTables::eloquent($methods)
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.settings.offer.partials.datatable.payment_method_action')
                        ->with(compact('data'));

                })
                ->make(true);
        } else {
            return abort(404);
        }
    }
}
