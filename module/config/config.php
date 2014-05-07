<?php

// hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][] = array('TemplateExtended\TemplateEngine', 'initialize');

if(!isset($GLOBALS['TL_HOOKS']['parseFrontendTemplate'])) {
	$GLOBALS['TL_HOOKS']['parseFrontendTemplate'] = array();
}

if(!isset($GLOBALS['TL_HOOKS']['parseBackendTemplate'])) {
	$GLOBALS['TL_HOOKS']['parseBackendTemplate'] = array();
}

// TemplateExtended inheritance can be enabled by template black or whitelist or gobal for every template
$GLOBALS['TEMPLATE_INHERITANCE'] = 'blacklist';


// copy black or whitelist for your template
//$GLOBALS['TEMPLATE_INHERITANCE_WHITELIST'][] = 'fe_page';
//$GLOBALS['TEMPLATE_INHERITANCE_BLACKLIST'][] = 'fe_page';