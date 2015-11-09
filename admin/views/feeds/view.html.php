<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewFeeds extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
        protected $total;


        public function display($tpl = null)
	{




		$this->items		=  $this->get( 'Data');
		$this->total		=  $this->get( 'Total');
		$this->pagination =  $this->get( 'Pagination' );
                //print_r($this->pagination);
                $this->state		= $this->get('State');
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		//$this->assignRef('items',		$items);
		//$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
	protected function addToolBar()
	{
		//JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
require_once JPATH_COMPONENT.'/helpers/submenu.php';
		SdrsssynicatorHelper::addSubmenu('feeds');
		//$text = 'Feed manager';
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		// Built the actions for new and existing records.
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
	}
}
?>