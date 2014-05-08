<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 07.05.14
 * Time: 10:28
 */

namespace TemplateExtended;


class TemplateHelper
{
	/**
	 * @var string
	 */
	protected $parent;

	/**
	 * @var array
	 */
	protected $blockNames = array();

	/**
	 * @var \Template
	 */
	protected $template;

	/**
	 * @var
	 */
	private $blockContents = array();


	/**
	 * @param $template
	 */
	private function __construct($template)
	{
		$this->template     = $template;
		$template->__helper = $this;

		foreach(array('block', 'endblock', 'extend', 'insert', 'parent') as $method) {
			$this->assignMethodToTemplate($method);
		}
	}

	/**
	 * @param $template
	 * @return static
	 */
	public static function forTemplate($template)
	{
		if($template->__helper) {
			return $template->__helper;
		}

		return new static($template);
	}


	/**
	 * @return \Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * @param $name
	 */
	public function extend($name)
	{
		$this->parent = $name;
	}


	/**
	 * @param $name
	 * @throws \Exception
	 */
	public function block($name)
	{
		$this->blockNames[] = $name;

		// Root template
		if($this->parent === null) {
			if (!array_key_exists($name, $this->blockContents)) {
				$this->blockContents[$name] = '[[TL_PARENT]]';
			}
			elseif (is_array($this->blockContents[$name])) {
				// Combine the contents of the child blocks
				$this->blockContents[$name] = array_reduce(
					$this->blockContents[$name] ?: array(),
					function($current, $parent) {
						return str_replace('[[TL_PARENT]]', $current, $parent);
					},
					'[[TL_PARENT]]'
				);
			}

			if (strpos($this->blockContents[$name], '[[TL_PARENT]]') !== false) {
				$arrChunks = explode('[[TL_PARENT]]', $this->blockContents[$name], 2);
				echo $arrChunks[0];
			}
			else {
				echo $this->blockContents[$name];
				// Start a output buffer to remove all block contents afterwards
				ob_start();
			}
		}

		// Child template
		else {
			// Clean the output buffer
			ob_end_clean();

			if (count($this->blockNames) > 1) {
				throw new \Exception('Nested blocks are not allowed for child templates');
			}

			// Start a new output buffer
			ob_start();
		}
	}


	/**
	 * @throws \Exception
	 */
	public function endblock()
	{
		if (empty($this->blockNames)) {
			throw new \Exception('You must start a block before you can end it');
		}

		$name = array_pop($this->blockNames);

		// Root template
		if ($this->parent === null) {
			if (strpos($this->blockContents[$name], '[[TL_PARENT]]') !== false) {
				$arrChunks = explode('[[TL_PARENT]]', $this->blockContents[$name], 2);
				echo $arrChunks[1];
			}
			else {
				// Remove all block contents because it was overwritten
				ob_end_clean();
			}
		}
		// Child template
		else {
			// prevent that it fails if other hook rendered an empty template
			if(is_string($this->blockContents[$name])) {
				$this->blockContents[$name] = array();
			}

			// Clean the output buffer
			$this->blockContents[$name][] = ob_get_contents();
			ob_end_clean();

			// Start a new output buffer
			ob_start();
		}
	}


	/**
	 * Insert parent content here
	 */
	public function parent()
	{
		echo '[[TL_PARENT]]';
	}


	/**
	 * @param $buffer
	 * @return mixed
	 */
	public function parse($buffer)
	{
		while($this->parent !== null) {
			// use different templates depending on original one so that other extension will get the expected template
			if($this->template instanceof \BackendTemplate) {
				$template = new BackendTemplate($this->parent, $this, $this->template->getFormat());
			}
			else {
				$template = new FrontendTemplate($this->parent, $this, $this->template->getFormat());
			}

			$template->setData($this->template->getData());
			$this->parent = null;

			$buffer = $template->parse($this->parent == $this->template->getName());
		}

		return $buffer;
	}


	/**
	 * @param $name
	 * @param array $data
	 */
	public function insert($name, array $data=array())
	{
		/** @var \Template $template */
		$class    = get_class($this->template);
		$template = new $class($name);
		$template->setData($data);

		echo $template->parse();
	}


	/**
	 * @param $method
	 * @throws \Exception
	 */
	private function assignMethodToTemplate($method)
	{
		if($this->template->$method !== null) {
			throw new \Exception("Template variable '$method'' already used");
		}

		$helper = $this;

		$this->template->$method = function() use ($helper, $method) {
			$arguments = func_get_args();
			return call_user_func_array(array($helper, $method), $arguments);
		};
	}

}