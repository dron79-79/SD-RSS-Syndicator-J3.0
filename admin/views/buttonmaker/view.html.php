<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewButtonMaker extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);		
	
	}
	protected function addToolBar()
	{
		//JRequest::setVar('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/submenu.php';
		SdrsssynicatorHelper::addSubmenu('buttonmaker');
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		JToolBarHelper::save();
		
		
	}
}
?>