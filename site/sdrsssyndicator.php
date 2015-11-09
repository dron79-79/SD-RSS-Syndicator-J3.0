<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

//defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.'/controllers/defaultcontroller.php');

// Require specific controller if requested
if($controller = JFactory::getApplication()->input->get('controller')) {
	require_once (JPATH_COMPONENT.'/controllers/'.$controller.'.php');
}

// Create the controller
$classname	= 'sdrsssyndicatorController'.$controller;
//die($classname	);
$controller = new $classname( );

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get('task'));


// Redirect if set by the controller
$controller->redirect();
?>
