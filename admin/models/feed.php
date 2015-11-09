<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die( 'Restricted access' );

//jimport( 'joomla.application.component.model' );
jimport('joomla.application.component.modellist');

//class sdrsssyndicatorModelFeed extends JModel JModelLegacy
class sdrsssyndicatorModelFeed extends JModelList
{
	protected $text_prefix = 'COM_SDRSSSYNDICATOR';
	var $_data = null;
	var $_sdata = null;
	var $_total = null;
	var $_pagination = null;
	var $_id = null;
	var $_sections;
   
	var $_exCategories;
	var $_seccatlist;
    

	function __construct()
	{
		parent::__construct();

		//global $option;
		//$mainframe = JFactory::getApplication();
                $app = JFactory::getApplication();
		// Get the pagination request variables
		//$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		//$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
                //$authorId = $app->getUserStateFromRequest($this->context.'.filter.author_id', 'filter_author_id');
		//$this->setState('filter.author_id', $authorId);
                $limit=10;
 $limitstart	=0;
 $limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', 10, 'int' );
		$limitstart	= $app->getUserStateFromRequest( 'global.list.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		//edit feed
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_sdata	= null;
	}
	/**
	 * Method to get the total number of feeds items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the feeds
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );

		}

		return $this->_pagination;
	}



	function _buildQuery()
	{
		$query = "SELECT * FROM `#__sdrsssyndicator_feeds`";
		return $query;
	}

	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));;
		}
		if (!$this->_data) {
			$this->_data = null;
		}

		return $this->_data;
	}


//Feed

	/**
	 * Method to remove a feed
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 * lvh
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM `#__sdrsssyndicator_feeds`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to (un)publish a feed
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 * lvh
	 */
	function publish($cid = array(), $publish = 1)
	{
		//$user 	= JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = "UPDATE `#__sdrsssyndicator_feeds` SET published='$publish'"
	. "\nWHERE id IN ($cids)";
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function getSData()
	{
		// Load the data
		if (empty( $this->_sdata )) {
			$query = "SELECT * FROM `#__sdrsssyndicator_feeds` WHERE id = $this->_id";
			$this->_db->setQuery( $query );
			$this->_sdata = $this->_db->loadObject();
		}
		if (!$this->_sdata) {
			$this->_sdata = new stdClass();
			$this->_sdata->id = 0;
		}
		return $this->_sdata;
	}

       
	function getExCategories()
	{
		if (empty( $this->_exCategories ))
		{
			$query = "SELECT c.id,  c.title AS title"
					. "\n FROM #__categories AS c"

					. "\n WHERE c.published = 1"
					. "\n AND c.extension = 'com_content'"
					. "\n ORDER BY c.id"
					;
			$this->_exCategories = $this->_getList( $query );
		}
		return $this->_exCategories;
	}

	

	function save()
	{
		
		$id = JFactory::getApplication()->input->get('id', '0', 'post', 'int');
		$a_msg_sectlist = JFactory::getApplication()->input->get('msg_sectlist', array(), 'post', 'array');
		$a_msg_excatlist = JFactory::getApplication()->input->get('msg_excatlist', array(), 'post', 'array');
                $a_msg_k2catlist = JFactory::getApplication()->input->get('msg_k2catlist', array(), 'post', 'array');

		$msg_sectlist  = implode(',', $a_msg_sectlist);
		$msg_excatlist  = implode(',', $a_msg_excatlist);
                $msg_k2catlist = implode(',', $a_msg_k2catlist);

		$feed_name = JFactory::getApplication()->input->get('feed_name', '', 'post', 'string');
		
		$feed_description = JFactory::getApplication()->input->get('feed_description', '', 'post', 'string');
       

		$feed_type = JFactory::getApplication()->input->get('feed_type', '', 'post', 'string');
		$feed_key = JFactory::getApplication()->input->get('feed_key', '', 'post', 'string');
		

		$yandex_genre = JFactory::getApplication()->input->get('yandex_genre', '', 'post', 'string');

		$feed_cache = JFactory::getApplication()->input->get('feed_cache', '', 'post', 'string');

		$feed_imgUrl = JFactory::getApplication()->input->get('feed_imgUrl', '', 'post', 'string');
       

		$feed_button = JFactory::getApplication()->input->get('feed_button', '', 'post', 'string');
      

		$feed_renderAuthorFormat = JFactory::getApplication()->input->get('feed_renderAuthorFormat', '', 'post', 'string');
		$feed_renderHTML   = JFactory::getApplication()->input->get('feed_renderHTML', '0', 'post', 'int');
		$feed_renderImages = JFactory::getApplication()->input->get('feed_renderImages', '0', 'post', 'int');
		$feed_categoryItem = JFactory::getApplication()->input->get('feed_categoryItem', '0', 'post', 'int');
		$msg_count = JFactory::getApplication()->input->get('msg_count', '', 'post', 'string');
		$msg_orderby=JFactory::getApplication()->input->get('msg_orderby', '', 'post', 'string');
		$msg_numWords = JFactory::getApplication()->input->get('msg_numWords', '0', 'post', 'int');
		$msg_FPItemsOnly = JFactory::getApplication()->input->get('msg_FPItemsOnly', '0', 'post', 'int');
		$msg_fulltext = JFactory::getApplication()->input->get('msg_fulltext', '0', 'post', 'int');
		$published = JFactory::getApplication()->input->get('published', '0', 'post', 'int');
		//VH Oct 27 2008
		$msg_exitems = JFactory::getApplication()->input->get('msg_exitems', '', 'post', 'string');

		$msg_contentPlugins = JFactory::getApplication()->input->get('msg_contentPlugins', '0', 'post', 'int');
		$msg_includeCats = JFactory::getApplication()->input->get('msg_includeCats', '0', 'post', 'int');

		$isNew = ($id<1);
		if($isNew)
			$query = "INSERT INTO #__sdrsssyndicator_feeds (`feed_name`,`feed_description`, `feed_type`, `feed_cache` ,`feed_imgUrl`,
					  `feed_button`, `feed_renderAuthorFormat`,  `feed_renderHTML`, `feed_renderImages` , `feed_categoryItem`, `msg_count` , `msg_orderby`,
					  `msg_numWords` , `msg_FPItemsOnly`, `msg_sectlist` , `msg_excatlist` , `msg_k2catlist`, `msg_includeCats`, `msg_fulltext` , `yandex_genre` , `msg_exitems` ,
					  `msg_contentPlugins`, `published`, `feed_key`)
						VALUES
						(
							'".$feed_name."',
							'".$feed_description."',
							'$feed_type',
							'$feed_cache',
							'".$feed_imgUrl."',
							'".$feed_button."',
							'$feed_renderAuthorFormat',
							'$feed_renderHTML',
							'$feed_renderImages',
							'$feed_categoryItem',
							'$msg_count',
							'$msg_orderby',
							'$msg_numWords',
							'$msg_FPItemsOnly',
							'$msg_sectlist',
							'$msg_excatlist',
                                                        '$msg_k2catlist',
							'$msg_includeCats',
							'$msg_fulltext',
							'$yandex_genre',
							'$msg_exitems',
							'$msg_contentPlugins',
							'$published',
							'$feed_key'
						)
				";
			else
				$query = "UPDATE #__sdrsssyndicator_feeds SET
							`feed_name` = '$feed_name',
							`feed_description` = '$feed_description',
							`feed_type` = '$feed_type',
							`feed_cache` = '$feed_cache',
							`feed_imgUrl` = '$feed_imgUrl',
							`feed_button` = '$feed_button',
							`feed_renderAuthorFormat` = '$feed_renderAuthorFormat',
							`feed_renderHTML` = '$feed_renderHTML',
							`feed_renderImages` = '$feed_renderImages',
							`feed_categoryItem` = '$feed_categoryItem',
							`msg_count` = '$msg_count',
							`msg_orderby` = '$msg_orderby',
							`msg_numWords` = '$msg_numWords',
							`msg_FPItemsOnly` = '$msg_FPItemsOnly',
							`msg_sectlist` = '$msg_sectlist',
							`msg_excatlist` = '$msg_excatlist',
                                                        `msg_k2catlist` = '$msg_k2catlist',
							`msg_includeCats` = '$msg_includeCats',
							`msg_fulltext` = '$msg_fulltext',
							`yandex_genre` = '$yandex_genre',
							`msg_exitems` = '$msg_exitems',
							`msg_contentPlugins` = '$msg_contentPlugins',
							`published` = '$published',
							`feed_key` = '$feed_key'
						WHERE id = $id
				";
		//print_r($query);die();
		$this->_db->setQuery($query);
		$this->_data = $this->_db->query();
		if($this->_data)
			return true;
		else
			return false;
	}

	function getDefaultData()
	{
		$config = $this->getInstance('config','sdrsssyndicatorModel');
		return $config->getData();
	}
    
	
}