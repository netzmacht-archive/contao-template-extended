<?php

namespace TemplateExtended;


class FrontendTemplate extends \FrontendTemplate
{
	/**
	 * @var TemplateHelper
	 */
	protected $_helper;


	/**
	 * @param string $strTemplate
	 * @param string TemplateHelper $helper
	 * @param string $strContentType
	 */
	public function __construct($strTemplate = '', TemplateHelper $helper, $strContentType = 'text/html')
	{
		parent::__construct($strTemplate, $strContentType);

		$this->_helper = $helper;
	}


	/**
	 * @param $name
	 */
	public function block($name)
	{
		$this->_helper->block($name);
	}


	/**
	 *
	 */
	public function endblock()
	{
		$this->_helper->endblock();
	}


	/**
	 *
	 */
	public function parent()
	{
		$this->_helper->parent();
	}


	/**
	 * @param $name
	 * @param array $data
	 */
	public function insert($name, array $data=array())
	{
		$this->_helper->insert($name, $data);
	}


	/**
	 * @param bool $defaultOnly
	 * @return string
	 */
	public function parse($defaultOnly=false)
	{
		if($this->strTemplate == '') {
			return '';
		}

		if($defaultOnly) {
			$template = $this->getDefaultPath($this->strTemplate, $this->strFormat);
		}
		else {
			$template = \TemplateLoader::getPath($this->strTemplate, $this->strFormat);
		}

		ob_start();
		include $template;
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}


	/**
	 * @throws \BadMethodCallException
	 */
	public function output($blnCheckRequest = false)
	{
		throw new \BadMethodCallException('Output is not allowed');
	}


	/**
	 * @param $template
	 * @param $format
	 * @return string
	 * @throws \Exception
	 */
	private function getDefaultPath($template, $format)
	{
		$file  = $template .  '.' . $format;
		$files = \TemplateLoader::getFiles();

		if (isset($files[$template]))
		{
			return TL_ROOT . '/' . $files[$template] . '/' . $file;
		}

		throw new \Exception('Could not find template "' . $template . '"');
	}

} 