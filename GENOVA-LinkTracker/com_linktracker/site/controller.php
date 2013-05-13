<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.controller');

class LinkTrackerController extends JControllerLegacy{
	public function track(){
		$link = JRequest::getVar('link', NULL);
		if(!$link){
			$uri = JFactory::getURI();
			$this->setRedirect($uri->base());
			return;
		}
		$model = $this->getModel('LinkTracker', 'LinkTrackerModel');
		$link = $model->process($link);
		$this->setRedirect((string)$link);	
	}
}