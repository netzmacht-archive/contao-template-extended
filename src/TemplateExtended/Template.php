<?php

namespace TemplateExtended;

/**
 * Class Template
 * @package ExtendedTemplate
 */
class Template
{

	/**
	 * @var \Template
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $sections = array();

	/**
	 * @var
	 */
	protected $child;

	/**
	 * @var
	 */
	protected $layout;


	/**
	 * @param \Template $template
	 */
	public function __construct(\Template $template)
	{
		$this->template = $template;
	}


	/**
	 * Open a section
	 *
	 * @param $name
	 */
	public function start($name)
	{
		$this->sections[] = $name;
		ob_start();
	}


	/**
	 * Close a section
	 */
	public function end()
	{
		if (!count($this->sections)) {
			throw new \LogicException('No open section found');
		}

		$section = array_pop($this->sections);
		$this->template->$section = ob_get_clean();
	}


	/**
	 * @param $name
	 */
	public function layout($name)
	{
		$this->layout = $name;
	}


	/**
	 * @return string
	 */
	public function getLayout()
	{
		return $this->layout;
	}


	/**
	 * @return \Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * Insert a sub template
	 *
	 * @param string $name
	 * @param array $data
	 * @param bool $output
	 *
	 * @return string
	 */
	public function insert($name, array $data=null, $output=true)
	{
		$template = clone $this->template;
		$template->setName($name);
		$template->setData($data);

		$buffer = $template->parse();

		if($output) {
			echo $buffer;
		}

		return $buffer;
	}


	/**
	 * Get Child content
	 *
	 * @return mixed
	 */
	public function child()
	{
		return $this->child;
	}


	/**
	 * Set the child content
	 * @param $child
	 */
	public function setChild($child)
	{
		$this->child = $child;
	}

}