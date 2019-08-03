<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	protected $dontRespond = [
		ValidationException::class
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception $exception
	 * @return void
	 */
	public function report(Exception $exception)
	{
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception)
	{
		if ($this->shouldRespond($request, $exception)) {
			$message = $exception->getMessage();

			$code = $exception->getCode() ?: 422;

			if ($exception instanceof TokenMismatchException) {
				$message = __('Your session has expired! Refresh to proceed.');
			}

			if ($request->expectsJson()) {
				return response()->json($message, $code);
			}

			return response($message, $code);
		}

		return parent::render($request, $exception);
	}


	/**
	 * Generate HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response|bool
	 */
	private function shouldRespond($request, $e)
	{
		$match = Arr::first($this->dontRespond, function ($type) use ($e) {
			return $e instanceof $type;
		});

		return is_null($match) && $request->ajax();
	}
}
