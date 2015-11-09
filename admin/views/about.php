<?php
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class sdrsssyndicatorViewAbout
{

	function __construct()
	{
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();		
		$this->about();
	}
	
	function about()
	{
		JHtml::_('bootstrap.tooltip');
		// Загружаем проверку формы.
		//JHtml::_('behavior.formvalidation');
		// Загружаем украшательства формы.
		//JHtml::_('formbehavior.chosen', 'select');
		?>
			<?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>
			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<div class="m">
							
			<p align="left">компонет SD RSS Syndicator, разработан студией веб дизайна Апрель</p>
			<p align="left">информацию по этому проекту смотрите на странице проекта <a href="http://www.sdaprel.ru/content/view/738/51/">SD RSS Syndicator</a></p>
			
			<p align="left">Copyright 2010, <a href="http://sdaprel.ru/" target="_blank">www.sdaprel.ru</a>.</p>
				<div class="clr"></div>
			</div>
			</form>
		<?php
	}
	protected function addToolBar()
	{
		//JRequest::setVar('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/submenu.php';
		SdrsssynicatorHelper::addSubmenu('info');
		//JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		
		
	}
}
?>
