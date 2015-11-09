<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class sdrsssyndicatorModelConfig extends JModelLegacy
{

	var $_data;

	function __construct()
	{
		parent::__construct();
		global $mainframe, $option;
	}

	function _buildQuery()
	{
		$query = ' SELECT * FROM #__sdrsssyndicator '.
					'  WHERE id = 1 '
		;

		return $query;
	}

	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = '';
		}
		return $this->_data;
	}

	function saveConfig(){
		$id = JFactory::getApplication()->input->get('id', '1', 'post', 'int');
		$msg = JFactory::getApplication()->input->get('msg', 'Get the latest news direct to your desktop', 'post', 'string');
        //$msg = $this->_db->Quote($this->_db->getEscaped($msg), false);
		$defaultType = JFactory::getApplication()->input->get('defaultType', '1.0', 'post', 'string');
		$count = JFactory::getApplication()->input->get('count', '10', 'post', 'string');
		$orderby = JFactory::getApplication()->input->get('orderby', 'rdate', 'post', 'string');
		$numWords = JFactory::getApplication()->input->get('numWords', '25', 'post', 'int');

		$cache = JFactory::getApplication()->input->get('cache', '3600', 'post', 'int');
                $tp = JFactory::getApplication()->input->get('tp', '4', 'post', 'int');
		$imgUrl = JFactory::getApplication()->input->get('imgUrl', '', 'post', 'string');
        //$imgUrl = $this->_db->Quote($this->_db->getEscaped($imgUrl), false);
		$renderAuthorFormat = JFactory::getApplication()->input->get('renderAuthorFormat', '1', 'post', 'string');
		$renderHTML = JFactory::getApplication()->input->get('renderHTML', '0', 'post', 'int');
		$categoryItem = JFactory::getApplication()->input->get('categoryItem', '0', 'post', 'int');
		$FPItemsOnly = JFactory::getApplication()->input->get('FPItemsOnly', '1', 'post', 'int');
		$description = JFactory::getApplication()->input->get('description', '', 'post', 'string');
        //$description = $this->_db->Quote($this->_db->getEscaped($description), false);

		$query = "UPDATE #__sdrsssyndicator SET msg = '$msg',
													defaultType='$defaultType',
													count = '$count',
													orderby = '$orderby',
													numWords = '$numWords',
													cache = '$cache',
                                                                                                        tp = $tp,
													imgUrl = '$imgUrl',
													renderAuthorFormat = '$renderAuthorFormat',
													renderHTML = '$renderHTML',
													categoryItem = '$categoryItem',
													FPItemsOnly = '$FPItemsOnly',
													description = '$description'
													WHERE id = $id";


		$this->_db->setQuery($query);
		$this->_data = $this->_db->query();

		if($this->_data)
			return true;
		else
			return false;
	}


}
