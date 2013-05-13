<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('JPATH_BASE') or die;

class plgSystemLinkTracker extends JPlugin{
	public function onAfterDispatch(){
		$app = JFactory::getApplication();
		if($app->isSite()){
			JHtml::script('plugins/system/linktracker/assets/js/linktracker.js', true);
		}
	}
}

