<?php
defined('_JEXEC') or die;
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

//jimport('joomla.application.component.controller');

class sdrsssyndicatorControllerButtonMaker extends JControllerLegacy
{

	var $_link = null;
	function __construct()
	{
		
		parent::__construct();
		$this->_link = 'index.php?option=com_sdrsssyndicator&task=buttonmaker';
	}
	
	function save()
	{		
		$model = $this->getModel('buttonmaker');
		$msg = $model->save($post);
		$is_ajaxed = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) ? ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") : false;
		if($is_ajaxed)
			exit($msg);		
		$this->setRedirect( $this->_link, $msg );
	}	
}
?>
