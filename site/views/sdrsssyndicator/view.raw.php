<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

//jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewsdrsssyndicator extends JViewLegacy
{
	function display($tpl = null)
	{
		
		$this->model = $this->getModel();

		$feed		=  $this->get( 'Data');
		$content	=  $this->get( 'Content');
        $tp	=  $this->get( 'Tp');
        		
		$this->id=$feed->id;
		$this->title=$feed->feed_name;
		$this->type=$feed->feed_type;
		$this->excatlist=$feed->msg_excatlist;
		$this->fulltext=$feed->msg_fulltext;
		$this->yandexgenre=$feed->yandex_genre;
		//$this->cat=$feed->msg_sectcat;
		$this->count=$feed->msg_count;
		$this->orderby=$feed->msg_orderby;
		$this->cache=$feed->feed_cache;
		$this->description= $feed->feed_description;
		$this->renderAuthorFormat=$feed->feed_renderAuthorFormat;
		$this->renderHTML= $feed->feed_renderHTML;
		$this->categoryItem=$feed->feed_categoryItem;
		$this->renderImages=$feed->feed_renderImages;
		$this->FPItemsOnly=$feed->msg_FPItemsOnly;
		$this->numWords=$feed->msg_numWords;
		$this->imgUrl=$feed->feed_imgUrl;
		$this->key=$feed->feed_key;
		$this->contentPlugins=$feed->msg_contentPlugins;
		
		$this->content=$content;
        
        $this->tp=$tp;
		
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/rss+xml');

		parent::display($tpl);
	}
}
?>