<?php

// hooks
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('TemplateExtended\TemplateEngine', 'initialize');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('TemplateExtended\TemplateEngine', 'parse');
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][]  = array('TemplateExtended\TemplateEngine', 'parse');


// TemplateExtended inheritance can be enabled by template black or whitelist or gobal for every template
$GLOBALS['TEMPLATE_INHERITANCE'] = \TemplateExtended\TemplateEngine::DISABLE_BY_BLACKLIST;

// copy black or whitelist for your template
//$GLOBALS['TEMPLATE_INHERITANCE_WHITELIST'][] = 'fe_page';
//$GLOBALS['TEMPLATE_INHERITANCE_BLACKLIST'][] = 'fe_page';