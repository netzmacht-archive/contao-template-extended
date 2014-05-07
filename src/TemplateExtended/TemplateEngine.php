<?php

namespace TemplateExtended;


class TemplateEngine
{
	const ENABLE_GLOBAL        = 'global';
	const ENABLE_BY_WHITELIST  = 'whitelist';
	const DISABLE_BY_BLACKLIST = 'blacklist';

	/**
	 * @var TemplateEngine
	 */
	protected static $instance;

	/**
	 * @var TemplateHelper[]|bool[]
	 */
	protected $templates = array();


	/**
	 * @return TemplateHelper
	 */
	public static function getInstance()
	{
		if(!static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * @param \Template $template
	 */
	public function initialize(\Template $template)
	{
		if($this->isEnabledForTemplate($template->getName())) {
			$this->templates[] = TemplateHelper::forTemplate($template);
		}
	}


	/**
	 * @param $buffer
	 * @param $name
	 * @return string
	 */
	public function parse($buffer, $name)
	{
		if($this->isEnabledForTemplate($name)) {
			$helper = array_pop($this->templates);
			/** @var TemplateHelper $helper */
			$buffer = $helper->parse($buffer);
		}

		return $buffer;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	private function isEnabledForTemplate($name)
	{
		switch($GLOBALS['TEMPLATE_INHERITANCE']) {
			case static::ENABLE_GLOBAL:
				return true;
				break;

			case static::DISABLE_BY_BLACKLIST:
				if(!isset($GLOBALS['TEMPLATE_INHERITANCE_BLACKLIST'])) {
					return true;
				}

				return !in_array($name, (array) $GLOBALS['TEMPLATE_INHERITANCE_BLACKLIST']);

				break;

			case static::ENABLE_BY_WHITELIST:
				if(!isset($GLOBALS['TEMPLATE_INHERITANCE_WHITELIST'])) {
					return false;
				}

				return in_array($name, (array) $GLOBALS['TEMPLATE_INHERITANCE_WHITELIST']);

				break;

			default:
				return false;
		}
	}

}