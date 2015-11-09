<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class sdrsssyndicatorControllerFeed extends JControllerLegacy
{

    protected $_link = null;
	public function __construct()
	{
		parent::__construct();
		$this->_link = 'index.php?option=com_sdrsssyndicator&task=feeds';
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply'  , 	'save' );
	}

	public function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		//$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( $this->_link );
	}

	public function edit()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		JRequest::setVar( 'view', 'feed' );
		JRequest::setVar( 'layout', 'feed'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$model = $this->getModel('feed');

		if ($model->save()) {
			$msg = JText::_( 'Feed Saved!' );
		} else {
			$msg = JText::_( 'Error Saving feed' );
		}
		if(isset($this->_task )and($this->_task== 'apply'))
		{
			$id = JRequest::getVar( 'id', '', 'post', 'string' );
			//die(JRequest::getVar('id' ));
			$this->_link = "index.php?option=com_sdrsssyndicator&task=edit&cid[]=$id&controller=feed";
		}
		$this->setRedirect( $this->_link, $msg );
	}

	public function publish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('feed');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect($this->_link);
	}

	public function unpublish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('feed');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect($this->_link);
	}

	public function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('feed');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect($this->_link);
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

}
?>
