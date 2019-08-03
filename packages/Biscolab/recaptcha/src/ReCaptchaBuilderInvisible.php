<?php
/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - ReCaptchaBuilderInvisible.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

namespace Biscolab\ReCaptcha;

/**
 * Class ReCaptchaBuilderInvisible
 * @package Biscolab\ReCaptcha
 */
class ReCaptchaBuilderInvisible extends ReCaptchaBuilder
{

	/**
	 * ReCaptchaBuilderInvisible constructor.
	 *
	 * @param string $api_site_key
	 * @param string $api_secret_key
	 */
	public function __construct(string $api_site_key, string $api_secret_key)
	{

		parent::__construct($api_site_key, $api_secret_key, 'invisible');
	}

	/**
	 * Write HTML <button> tag in your HTML code
	 * Insert before </form> tag
	 *
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function htmlFormButton($text = 'Submit', $attributes = []): string
	{
		$attributes = $this->prepareAttributes($attributes);
		$button = sprintf('<button%s>%s</button>', $this->buildAttributes($attributes), $text);
		return $button;
	}

	/**
	 * Prepare HTML attributes and assure that the correct classes and attributes for captcha are inserted.
	 *
	 * @param array $attributes
	 *
	 * @return array
	 */
	protected function prepareAttributes(array $attributes)
	{
		if (!isset($attributes['class'])) {
			$attributes['class'] = '';
		}

		$attributes['class'] = 'g-recaptcha ' . $attributes['class'];
		$attributes['data-sitekey'] = $this->api_site_key;
		$attributes['data-callback'] = "biscolabLaravelReCaptcha";

		return $attributes;
	}

	/**
	 * Build HTML attributes.
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	protected function buildAttributes(array $attributes)
	{
		$html = [];

		foreach ($attributes as $key => $value) {
			$html[] = $key . '="' . $value . '"';
		}

		return count($html) ?
			' ' . implode(' ', $html) :
			'';
	}

}