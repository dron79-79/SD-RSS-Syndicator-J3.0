<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die( 'Restricted access' );

//jimport( 'joomla.application.component.model' );

class sdrsssyndicatorModelsdrsssyndicator extends JModelLegacy
{

	private $_data = null;
	private $_id = null;
	private $_content = null;
   
	private $_key = null;
    
    private $_tp=null;
	private $_video=null;

	function __construct()
	{


		//global $option;

		$id = JFactory::getApplication()->input->get('feed_id',  0, '', 'int');
		$this->setId((int)$id);
		//$this->_key = $feed->feed_key;
                parent::__construct();
	}

	public function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	private function _buildQuery()
	{
		$query = "SELECT * FROM `#__sdrsssyndicator_feeds` WHERE id = $this->_id";
		return $query;
	}

	public function getData()
	{
		$db = JFactory::getDBO();
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$db->setQuery( $query );
			$this->_data = $db->loadObject();
		}
		if (!$this->_data || $this->_data->published == 0) {
			$this->_data = array();
		}
		return $this->_data;
	}


	public function getContent()
	{

		if (null === ($feed = $this->getData())) {
		    JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'Error Loading Modules' ) . $db->getErrorMsg());
		    return false;
		}

		$seclist = $feed->msg_sectlist;
		$FPItemsOnly = $feed->msg_FPItemsOnly;
		$inclExclCatList = $feed->msg_includeCats;
		$excatlist = $feed->msg_excatlist;
		$exitems = str_replace(" ", "", $feed->msg_exitems);
		$count = $feed->msg_count;

		$db = JFactory::getDBO();
	    $date = JFactory::getDate();
        $now = $date->toSql();

		switch (strtolower( $feed->msg_orderby )) {
			case 'date':
				$orderby = "a.created";
				break;
			case 'rdate':
				$orderby = "a.created DESC";
				break;
			case 'mdate':
				$orderby = "a.modified";
				break;
			case 'mrdate':
				$orderby = "a.modified DESC";
				break;

			case 'artord':
				$orderby = "a.ordering";
				break;
			default:
				$orderby = "a.created";
				break;
		}

		/* SELECT construction */
		$queryUncat = "";//Oct 25 2008: include uncategories
		$query 	=  "SELECT u.id as userid, c.id as catid,  a.id as id, a.*, a.introtext as itext, a.fulltext as mtext, u.name AS author,  u.email as authorEmail, a.created_by_alias as authorAlias, a.created AS dsdate, c.title as catName,"
		//Oct 24 2008: include slug and catslug for work with JRoute
				. 'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
				. 'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug'
			;
		/* FROM */
		$query	.=  "\nFROM #__content AS a"
			;

		$query 	.= "\nLEFT JOIN #__users AS u ON u.id = a.created_by"
			.  "\nLEFT JOIN `#__categories` AS c on c.id = a.catid "
		//	.  "\nLEFT JOIN `#__sections` AS s on s.id = c.section "
			;
		/* WHERE construction  */
		$query	.= "\n WHERE a.state='1'";
		/* JOIN construction */
		if (intval($FPItemsOnly)==1) {
			// frontpage Items only
			$query  .= "\n AND a.id IN (SELECT content_id FROM #__content_frontpage)";
		} elseif (intval($FPItemsOnly)==2) {
			// all articles except frontpage ones
			$query  .= "\n AND a.id NOT IN (SELECT content_id FROM #__content_frontpage)";
		}

		if ($exitems != "") {
			$query	.= "\n AND a.id NOT IN (" . $exitems . ")";
		}

		if ($excatlist!=="") {
			if ($inclExclCatList){
				$query	.= "\n AND c.id IN (" . $excatlist . ")";
			} else {
				$query	.= "\n AND c.id NOT IN (" . $excatlist . ")";
			}
		}

	    $nullDate    = $db->getNullDate();
		$query	.= "\n AND a.access <= 1"	// item only public access check
			.  "\n AND (c.access <= 1 $queryUncat) "	// category only public access check
		//	.  "\n AND (s.access <= 0 $queryUncat)"	// section only public access check
			.  "\n" . 'AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).')'
			.  "\n" . 'AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).')'
		//	.  $prkey
			;
		/* ORDER BY, LIMIT ...  construction */
		$query	.= "\nORDER BY $orderby"
			.  ($count ? (" LIMIT " . $count) : "")
			;



		if (empty( $this->_content )) {
			$db->setQuery( $query );
			$this->_content = $db->loadObjectList();
		}
		if (!$this->_content) {
			$this->_content = array();
		}

		return $this->_content;

	}



	
	

    public function getTp()
	{
		//print_r('zahod');
            if (empty( $this->_tp ))
		{
			$query = "SELECT `tp`"
					. "\n FROM `#__sdrsssyndicator`"
					. "\n WHERE `id` = 1"
					;
			$this->_tp = $this->_getList( $query );
                        $this->_tp=$this->_tp[0]->tp;
                        //print_r($this->_tp);

		}

		return $this->_tp;

	}
	public function getVideo($id)
	{
		//print_r('zahod');
            if (empty( $this->_video ))
		{

			$query = "SELECT `id`, `video`, `thumb`"
					. "\n FROM `#__allvideoshare_videos`"
					. "\n WHERE `access`='public' and `published` = 1 and `id` = ".$id;
			$this->_video = $this->_getList( $query );
                                     //print_r($this->_tp);

		}

		return $this->_video;

	}
	
}
?>