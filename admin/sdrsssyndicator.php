<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Make sure the user is authorized to view this page
/*$user = & JFactory::getUser();
if (!$user->authorize( 'com_sdrsssyndicator', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}
*/
//
//defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_sdrsssyndicator'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
$controller_n = JFactory::getApplication()->input->get('controller');
// Execute the task.
//$controller	= JControllerLegacy::getInstance($controller_n);

//$controller->execute(JFactory::getApplication()->input->get('task'));
//$controller->redirect();



// Require the base controller
require_once (JPATH_COMPONENT.'/controllers/defaultcontroller.php');

// Require specific controller if requested
if($controller_n) {
	require_once (JPATH_COMPONENT.'/controllers/'.$controller_n.'.php');
}

//die('shit');
// Create the controller
$classname	= 'sdrsssyndicatorController'.$controller_n;
$controller_n = new $classname( );

// Perform the Request task
$controller_n->execute(JFactory::getApplication()->input->get('task'));
//print_r('ffff');die();
// Redirect if set by the controller
$controller_n->redirect();

?>
