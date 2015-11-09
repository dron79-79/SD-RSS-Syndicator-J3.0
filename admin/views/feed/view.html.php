<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewFeed extends JViewLegacy
{
	function display($tpl = null)
	{
		$feed = $this->get('SData');
		
		$exCategories = $this->get('ExCategories');
		$isNew = ($feed->id<1);
		$text = $isNew ? 'Новый канал':'Изменить канал: '. $feed->feed_name;
		
		$lists = array();
		
			

		if ($isNew)  {
			JToolBarHelper::cancel();
			$default = $this->get('DefaultData');
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'close' );
		}


		//rss type list
		// new cod Dashko Andrey
		$rssType[] = JHTML::_('select.option', 'YANDEX','RSS 2.0 vs Яндекс');
		$rssType[] = JHTML::_('select.option', 'RAMBLER','RSS 2.0 vs Рамблер');
		$rssType[] = JHTML::_('select.option', 'MAIL','RSS 2.0 vs Майл');
		// end cod
		$rssType[] = JHTML::_('select.option', '2.0','RSS 2.0');
		$rssType[] = JHTML::_('select.option', '1.0','RSS 1.0');
		$rssType[] = JHTML::_('select.option', '0.91','RSS 0.91');
		$rssType[] = JHTML::_('select.option', 'ATOM','ATOM');
		$rssType[] = JHTML::_('select.option', 'OPML','OPML');
		$rssType[] = JHTML::_('select.option', 'MBOX','MBOX');
		$rssType[] = JHTML::_('select.option', 'HTML','HTML');
		$rssType[] = JHTML::_('select.option', 'JS','JS');

		$lists['rssTypeList'] = JHTML::_('select.genericlist', $rssType, 'feed_type', 'class="inputbox"', 'value', 'text', $isNew ? $default->defaultType : $feed->feed_type, 'feed_type');

		$rssYandexGenre[] = JHTML::_('select.option', '','не используется');
		$rssYandexGenre[] = JHTML::_('select.option', 'lenta','короткое новостное сообщение');
		$rssYandexGenre[] = JHTML::_('select.option', 'message','развёрнутое новостное сообщение');
		$rssYandexGenre[] = JHTML::_('select.option', 'article','статья');
		$rssYandexGenre[] = JHTML::_('select.option', 'interview','интервью');
		$lists['rssYandexGenreList'] = JHTML::_('select.genericlist', $rssYandexGenre, 'yandex_genre', 'class="inputbox"', 'value', 'text', $isNew ? 'article': $feed->yandex_genre );


		$fulltext[] = JHTML::_('select.option', "5","(только для Яндекса, Рамблера и Майла) Введение + основная статья");
		$fulltext[] = JHTML::_('select.option', "4","(только для Яндекса, Рамблера и Майла) только полный текст без введения");
		$fulltext[] = JHTML::_('select.option', "0","Только вводный текст");
		$fulltext[] = JHTML::_('select.option', "1","Вводный текст + ссылка читать дальше");
		$fulltext[] = JHTML::_('select.option', "2","Вводный текст + Полный текст");
		$fulltext[] = JHTML::_('select.option', "3","Только полный текст");


		$lists['fulltextlist'] = JHTML::_('select.genericlist', $fulltext, 'msg_fulltext', 'class="inputbox"', 'value', 'text',  $isNew ? '1': $feed->msg_fulltext );

		$orderings[] = JHTML::_('select.option', 'date','Дата создания по возрастанию');
		$orderings[] = JHTML::_('select.option', 'rdate','Дата создания по убыванию');
		$orderings[] = JHTML::_('select.option', 'mdate','Дата модификации по возрастанию');
		$orderings[] = JHTML::_('select.option', 'mrdate','Дата модификации по убыванию');
		$orderings[] = JHTML::_('select.option', 'artord','Порядок статей Joomla');
		$lists['orderingList'] = JHTML::_('select.genericlist', $orderings, 'msg_orderby', 'class="inputbox"', 'value', 'text', $isNew ? $default->orderby : $feed->msg_orderby, 'msg_orderby');

		$numWords[] = JHTML::_('select.option','0','Все');
		for ($i=25;$i<=250;$i+=25) {
			$numWords[] = JHTML::_('select.option',$i,$i);
		}
		$lists['numWordsList'] = JHTML::_('select.genericList', $numWords, 'msg_numWords', 'class="inputbox"','value', 'text', $isNew ? $default->numWords : $feed->msg_numWords, 'msg_numWords' );

		$FPItemsOnly[] = JHTML::_( 'select.option', '0','Все статьи');
    $FPItemsOnly[] = JHTML::_( 'select.option', '1','Статьи опубликованные на главной');
		$FPItemsOnly[] = JHTML::_( 'select.option', '2','Статьи не опубликованные на главной');
		$lists['FPItemsOnlyList'] =JHTML::_( 'select.genericList',$FPItemsOnly, 'msg_FPItemsOnly', 'class="inputbox"','value', 'text',$isNew ? $default->FPItemsOnly : $feed->msg_FPItemsOnly, 'msg_FPItemsOnly' );

		$yesNoList[]   = JHTML::_( 'select.option', "1","Да");
		$yesNoList[]   = JHTML::_( 'select.option', "0","Нет");
		$lists['renderImagesList'] = JHTML::_( 'select.genericList', $yesNoList, 'feed_renderImages', 'class="inputbox"','value', 'text',$isNew ? '1' : $feed->feed_renderImages );
	  $lists['renderPublishedList'] = JHTML::_( 'select.genericList', $yesNoList, 'published', 'class="inputbox"','value', 'text',$isNew ? NULL : $feed->published);
		$lists['renderHTMLList'] =JHTML::_( 'select.genericList',$yesNoList, 'feed_renderHTML', 'class="inputbox"','value', 'text', $isNew ? $default->renderHTML : $feed->feed_renderHTML , 'feed_renderHTML');
		$lists['renderAuthorList'] = JHTML::_('select.genericList', $yesNoList, 'feed_renderAuthorFormat', 'class="inputbox"','value', 'text', $isNew ? $default->renderAuthorFormat : $feed->feed_renderAuthorFormat, 'feed_renderAuthorFormat' );

		$rssfeed_categoryItem[] = JHTML::_('select.option', "0", "Не использовать");
		$rssfeed_categoryItem[] = JHTML::_('select.option', "2", "Категория Joomla");
		$lists['renderCategoryList'] =JHTML::_( 'select.genericList',$rssfeed_categoryItem, 'feed_categoryItem', 'class="inputbox"','value', 'text', $isNew ? $default->categoryItem : $feed->feed_categoryItem , 'feed_categoryItem');

		$includeCats[] = JHTML::_( 'select.option', "0","Исключить выбранные категории");
		$includeCats[] = JHTML::_( 'select.option', "1","Включить выбранные категории");
		$lists['includeCats'] = JHTML::_( 'select.genericList', $includeCats, 'msg_includeCats', 'class="inputbox"','value', 'text',$isNew ? NULL : $feed->msg_includeCats);



		
		if($isNew)
			$exCatSelected = '';
		else
			$exCatSelected = explode(',',$feed->msg_excatlist);

		$exCatOptions[] = JHTML::_('select.option', "",'Не выбирать');
		$exCatOptions[] = JHTML::_('select.option', "0","Не категоризированные");
		foreach($exCategories as $exCategory)
		{
			$exCatOptions[] = JHTML::_('select.option', $exCategory->id,$exCategory->title);
		}

		$lists['excludedcatlist'] = JHTML::_( 'select.genericList', $exCatOptions, 'msg_excatlist' . '[]', 'class="inputbox" style="width: 500px;" multiple="true" size="45" ', 'value', 'text', $exCatSelected );

		//Feedbutton images uit de directory laden
		$button_path = JPATH_ROOT. "/components/com_sdrsssyndicator/assets/images/buttons";
		$dir = @opendir($button_path);
		$button_images = array();
		$button_col_count = 0;

		while( $file = @readdir($dir) )
		{
			if( $file != '.' && $file != '..' && is_file($button_path . '/' . $file) && !is_link($button_path . '/' . $file) )
			{
				if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file) )
				{
				   $button_images[$button_col_count] = $file;
				   $button_name[$button_col_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $file)));
				   $buttons[] = JHTML::_( 'select.option', $button_images[$button_col_count], $button_name[$button_col_count]);
				   $button_col_count++;
				}
			}
		}
		@closedir($dir);
		$lists['feedButtons'] = JHTML::_( 'select.genericList', $buttons, 'feed_button', 'onchange="loadButton(this)" class="inputbox" ','value', 'text',$isNew ? 'rss20.gif' : $feed->feed_button);
        
		//Editor
		$editor  = JFactory::getEditor();

		$this->assignRef('id', $feed->id);
		$this->assignRef('name', $feed->feed_name);
                if($isNew)
                    {$this->assignRef('count',$default->count);}
                    else {$this->assignRef('count',$feed->msg_count);}
		// $feed->msg_count = $isNew? $default->count:$feed->msg_count);
		//$this->assignRef('cache', $feed->feed_cache = $isNew? $default->cache:$feed->feed_cache);
                   if($isNew)
                    {$this->assignRef('cache',$default->cache);}
                    else {$this->assignRef('cache',$feed->feed_cache);}
		$this->assignRef('imgUrl', $feed->feed_imgUrl);
		$this->assignRef('key', $feed->feed_key);
		$isNew? $feed->feed_button='rss20.gif':$feed->feed_button;
		$this->assignRef('BtnImgUrl', $feed->feed_button);
		$this->assignRef('exitems', $feed->msg_exitems);
		//$this->assignRef('description', $feed->feed_description = $isNew ? $default->description : $feed->feed_description);
		if($isNew)
                    {$this->assignRef('description',$default->description);}
                    else {$this->assignRef('description',$feed->feed_description);}
                $this->assignRef('editor', $editor);
		$this->assignRef('lists', $lists);
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolBar()
	{
		//JRequest::setVar('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/submenu.php';
		SdrsssynicatorHelper::addSubmenu('feed');
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		
	}
}
?>