<?php

/**
 * Register engine hooks
 */
$GLOBALS['TL_HOOKS']['parseTemplate']           = array('TemplateExtended\Engine', 'prepare');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate']   = array('TemplateExtended\Engine', 'render');
$GLOBALS['TL_HOOKS']['parseBackendTemplate']    = array('TemplateExtended\Engine', 'render');