<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die;
//jimport('joomla.application.component.controller');

class sdrsssyndicatorController extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('info', 'info');
		$this->registerTask('buttonmaker', 'buttonmaker');
        $this->registerTask('genbtn', 'gen_button');
		$this->registerTask('config', 'config');
		$this->registerTask('feeds', 'feeds');
		$this->registerDefaultTask('feeds');
	}

	//function display()
	//{
	//	parent::display();
	//}
	public function display($cachable = false, $urlparams = false)
	{
	parent::display();
	}
	public function info()
	{
		require_once(JPATH_COMPONENT . '/views/about.php');
		new sdrsssyndicatorViewAbout();
	}

	public function buttonmaker()
	{

		JRequest::setVar('view','buttonmaker');
		JRequest::setVar('layout','form');

		$this->display();
	}

	public function config()
	{
		JRequest::setVar('view','config');
		JRequest::setVar('layout','config');

		$this->display();
	}

	public function feeds()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('feeds',$viewType,'sdrsssyndicatorView');
		$model = $this->getModel('feed');
		if(!JError::isError($model))
		{
			$view->setModel($model,true);
		}
		$view->setLayout('feeds');
		$view->display();

	}

    public function gen_button()
    {
        JRequest::setVar( 'view', 'buttonmaker' );
		JRequest::setVar( 'layout', 'button'  );

		parent::display();
    }




}
?>
