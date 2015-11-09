<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

//jimport( 'joomla.application.component.model' );

class sdrsssyndicatorModelButtonMaker extends JModelLegacy
{	
	function __construct()
	{
		parent::__construct();		
	}

	function save(){	
print_r('ggggg');	die();
		$url = urldecode(JFactory::getApplication()->input->get('imgurl', '', 'post', 'string'));
		$imgName =  urldecode(JFactory::getApplication()->input->get('imgname', '', 'post', 'string'));
		$savePath = JPATH_ROOT . '/images/stories';
		if(!$url || !$imgName){
			return 'Image invalid!';			
		}
		if(!file_exists($savePath) || !is_readable($savePath) || !is_writable($savePath)){ 	
			return 'Cannot access images/stories directory!';			
		}
		//save image
		file_put_contents($savePath.'/'.$imgName, file_get_contents($url));
		if(file_exists($savePath.'/'.$imgName))
			return 'Image saved! link: <a href="'.JURI::root().'images/stories/'.$imgName.'" title="copy this url to use">'.JURI::root().'images/stories/'.$imgName.'</a>';
		else
			return "There's an error while save image.Please try again!";	
	}
}
