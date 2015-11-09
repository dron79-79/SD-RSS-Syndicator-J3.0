<?php defined('_JEXEC') or die('Restricted access');
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

jimport('joomla.application.component.controller');

class sdrsssyndicatorControllerConfig extends JControllerLegacy
{

	function __construct()
	{
		parent::__construct();
	}

	function save()
	{
		$model = $this->getModel('config');
		if ($model->saveConfig($post)) {
			$msg = JText::_( 'Settings Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Settings' );
		}
		
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_sdrsssyndicator&task=config';
		$this->setRedirect($link, $msg);
	}
}
?>
