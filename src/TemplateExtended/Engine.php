<?php

namespace TemplateExtended;


class Engine
{

	/**
	 * @var Engine
	 */
	protected static $instance;

	/**
	 * @var Template[]|bool[]
	 */
	protected $templates = array();


	/**
	 * @return Engine
	 */
	public static function getInstance()
	{
		if(!static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Prepare Engine
	 *
	 * Called by parseTemplate hook
	 *
	 * @param \Template $template
	 */
	public function prepare(\Template $template)
	{
		$name = $template->getName();

		if(!isset($this->templates[$name]) || $this->templates[$name] !== true) {
			return;
		}

		$extended               = new Template($template);
		$template->__tpl        = $extended;
		$this->templates[$name] = $extended;

		// enable callables for Contao 3.2
		if(version_compare(VERSION, '3.2', '>=')) {
			$template->__start = function($name) use($extended) {
				$extended->start($name);
			};

			$template->__end = function() use($extended) {
				$extended->end();
			};

			$template->__layout = function($name) use($extended) {
				$extended->layout($name);
			};

			$template->__child  = function() use($extended) {
				return $extended->child();
			};

			$template->__insert = function() use($extended) {
				return $extended->child();
			};
		}
	}


	/**
	 * @param $buffer
	 * @param $name
	 * @return mixed
	 */
	public function render($buffer, $name)
	{

		if(!isset($this->templates[$name])) {
			return $buffer;
		}

		$extended = $this->templates[$name];
		$layout   = $extended->getLayout();

		if($layout) {
			$template = $extended->getTemplate();
			$template = clone $template;
			$template->setName($layout);

			$this->templates[$layout] = true;
			$this->prepare($template);
			$this->templates[$layout]->setChild($buffer);

			$buffer = $template->parse();
		}

		// free template name
		$this->templates[$name] = true;

		return $buffer;
	}

}
