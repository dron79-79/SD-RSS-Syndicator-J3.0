<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewsdrsssyndicator extends JView
{
	function display($tpl = null)
	{
		$feed		= $this->get( 'Data');
		$content	= $this->get( 'Content');
                $isk2 = $this->get ('Isk2');

		if ($isk2 == true)
		{
			$contentk2	= $this->get( 'Contentk2');
		}
		else
		{
			$contentk2 = false;
		}
		$menuItemArray = $this->get('MenuItemArray');
		$this->assignRef('id', $feed->id);
		$this->assignRef('title', $feed->feed_name);
		$this->assignRef('type', $feed->feed_type);
		$this->assignRef('sectlist', $feed->msg_sectlist);
		$this->assignRef('excatlist', $feed->msg_excatlist);
		$this->assignRef('fulltext', $feed->msg_fulltext);
		$this->assignRef('yandexgenre', $feed->yandex_genre);
		$this->assignRef('cat', $feed->msg_sectcat);
		$this->assignRef('count', $feed->msg_count);
		$this->assignRef('orderby', $feed->msg_orderby);
		$this->assignRef('cache', $feed->feed_cache);
		$this->assignRef('description', $feed->feed_description);
		$this->assignRef('renderAuthorFormat', $feed->feed_renderAuthorFormat);
		$this->assignRef('renderHTML', $feed->feed_renderHTML);
		$this->assignRef('categoryItem', $feed->feed_categoryItem);
		$this->assignRef('renderImages', $feed->feed_renderImages);
		$this->assignRef('FPItemsOnly', $feed->msg_FPItemsOnly);
		$this->assignRef('numWords', $feed->msg_numWords);
		$this->assignRef('imgUrl', $feed->feed_imgUrl);
		$this->assignRef('key', $feed->feed_key);
		$this->assignRef('contentPlugins', $feed->msg_contentPlugins);

    //TODO - add these columns if enough people request them
    //$this->assignRef('sdRSSFeedRowPlugins', $feed->msg_sdRSSFeedRowPlugins);
		//$this->assignRef('sdRSSFeedPlugins', $feed->feed_sdRSSFeedPlugins);

		$this->assignRef('content', $content);
                 $this->assignRef('contentk2', $contentk2);
		$this->assignRef('isk2', $isk2);
		$this->assignRef('menuitemarray',$menuItemArray );
		parent::display($tpl);
	}
}
?>