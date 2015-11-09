<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewConfig extends JViewLegacy
{
	function display($tpl = null)
	{
		//JToolBarHelper::save();
		$configs  = $this->get('Data');
		//$text = 'Настройки по умолчанию';
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'config.png' );

		$lists = array();

		$rssType[] = JHTML::_('select.option', 'YANDEX','Яндекс');
		$rssType[] = JHTML::_('select.option', 'RAMBLER','Рамблер');
		$rssType[] = JHTML::_('select.option', '2.0','RSS 2.0');
		$rssType[] = JHTML::_('select.option', '1.0','RSS 1.0');
		$rssType[] = JHTML::_('select.option', '0.91','RSS 0.91');
		$rssType[] = JHTML::_('select.option', 'ATOM','ATOM');
		$rssType[] = JHTML::_('select.option', 'OPML','OPML');
		$rssType[] = JHTML::_('select.option', 'MBOX','MBOX');
		$rssType[] = JHTML::_('select.option', 'HTML','HTML');
		$rssType[] = JHTML::_('select.option', 'JS','JS');

		$lists['rssTypeList'] = JHTML::_('select.genericlist', $rssType, 'defaultType', 'class="inputbox"', 'value', 'text', $configs->defaultType ? $configs->defaultType : 'YANDEX', 'defaultType');

		$orderings[] = JHTML::_('select.option', 'date','Дата создания по возрастанию');
		$orderings[] = JHTML::_('select.option', 'rdate','Дата создания по убыванию');
		$orderings[] = JHTML::_('select.option', 'mdate','Дата модификации по возрастанию');
		$orderings[] = JHTML::_('select.option', 'mrdate','Дата модификации по убыванию');
		$orderings[] = JHTML::_('select.option', 'catsect','порядок по разделу, категории Joomla');
		$orderings[] = JHTML::_('select.option', 'artord','Порядок статей Joomla');

		$lists['orderingList'] = JHTML::_('select.genericlist', $orderings, 'orderby', 'class="inputbox"', 'value', 'text', $configs->orderby, 'orderby');

		$numWords[] = JHTML::_('select.option','0','All');
		for ($i=25;$i<=250;$i+=25) {
			$numWords[] = JHTML::_('select.option',$i,$i);
		}
		$lists['numWordsList'] = JHTML::_('select.genericList', $numWords, 'numWords', 'class="inputbox"','value', 'text', $configs->numWords,  'numWords');

		$authorformats[] = JHTML::_( 'select.option', '1','Yes');
		$authorformats[] = JHTML::_( 'select.option', '0','No');
		$lists['renderAuthorList'] = JHTML::_('select.genericList', $authorformats, 'renderAuthorFormat', 'class="inputbox"','value', 'text',$configs->renderAuthorFormat );

		$renderHTML[] = JHTML::_( 'select.option', '1','Yes');
		$renderHTML[] = JHTML::_( 'select.option', '0','No');
		$lists['renderHTMLList'] =JHTML::_( 'select.genericList',$renderHTML, 'renderHTML', 'class="inputbox"','value', 'text',$configs->renderHTML );

		$categoryItem[] = JHTML::_('select.option', "0", "Не использовать");
		$categoryItem[] = JHTML::_('select.option', "1", "Раздел Joomla");
		$categoryItem[] = JHTML::_('select.option', "2", "Категория Joomla");
		$lists['categoryItemList'] =JHTML::_( 'select.genericList',$categoryItem, 'categoryItem', 'class="inputbox"','value', 'text',$configs->categoryItem );

		$FPItemsOnly[] = JHTML::_( 'select.option', '0','All items');
    $FPItemsOnly[] = JHTML::_( 'select.option', '1','Front page items only');
		$FPItemsOnly[] = JHTML::_( 'select.option', '2','Non-frontpage items only');
		$lists['FPItemsOnlyList'] =JHTML::_( 'select.genericList',$FPItemsOnly, 'FPItemsOnly', 'class="inputbox"','value', 'text',$configs->FPItemsOnly );

    $this->assignRef('id', $configs->id);
		$this->assignRef('msg', $configs->msg);
		$this->assignRef('defaultType', $lists['rssTypeList']);
		$this->assignRef('count', $configs->count);
		$this->assignRef('orderby', $lists['orderingList']);
		$this->assignRef('numWords', $lists['numWordsList']);
		$this->assignRef('renderAuthorFormat', $lists['renderAuthorList']);
		$this->assignRef('renderHTML', $lists['renderHTMLList']);
		$this->assignRef('categoryItem', $lists['categoryItemList']);
		$this->assignRef('FPItemsOnly', $lists['FPItemsOnlyList']);
		$this->assignRef('cache', $configs->cache);
                $this->assignRef('tp', $configs->tp);
		$this->assignRef('imgUrl', $configs->imgUrl);
		$this->assignRef('description', $configs->description);
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolBar()
	{
		//JRequest::setVar('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/submenu.php';
		SdrsssynicatorHelper::addSubmenu('config');
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		JToolBarHelper::save();
		//JToolBarHelper::cancel();
	}
}
?>