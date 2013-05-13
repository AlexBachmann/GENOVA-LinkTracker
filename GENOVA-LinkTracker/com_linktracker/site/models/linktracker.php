<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.model');

class LinkTrackerModelLinkTracker extends JModelLegacy {
	public function process($link){
		$user = JFactory::getUser();
		$link = JFactory::getUri($link);

		//First let's sort the query variables
		$vars = $link->getQuery(true);
		ksort($vars);
		$link->setQuery($vars);
		
		//Now let's check, if this link already exists in our database
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__lt_links')->where('url = '.$db->quote((string)$link));
		$db->setQuery($query);
		$id = $db->loadResult();
		if(!$id){
			//If this link does not exist yet, we are adding it
			$query = $db->getQuery(true);
			$query->insert('#__lt_links');
			$query->columns(array('url', 'scheme', 'domain', 'path', 'query'));
			$query->values($db->quote((string)$link).','.$db->quote($link->getScheme()).','.$db->quote($link->getHost()).','.$db->quote($link->getPath()).','.$db->quote($link->getQuery()));
			$db->setQuery($query);
			$db->query();
			$id = $db->insertid();
		}
		if(!$id){
			return $this->addAffiliate($link);
		}
		//Now we log the click in the database
		if(!$user->id){
			$user_id = 0;
		}else{
			$user_id = $user->id;
		}
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = JFactory::getDate();
		$query = $db->getQuery(true);
		$query->insert('#__lt_clicks');
		$query->columns(array('url_id', 'user', 'ip', 'time'));
		$query->values($db->quote($id).','.$db->quote($user_id).','.$db->quote($ip).','.$db->quote($date->toMySQL()));
		$db->setQuery($query);
		$db->query();
		
		return $this->addAffiliate($link);
	}
	protected function addAffiliate($link){
		$app = JFactory::getApplication();
		$params = $app->getParams();
		if($params->get('use_amazon') && $params->get('amazon_tracking_id')){
			$possibleHosts = array(
						'www.amazon.com', 'amazon.com',
						'www.amazon.de', 'amazon.de',
						'www.amazon.es', 'amazon.es',
						'www.amazon.fr', 'amazon.fr',
						'www.amazon.co.uk', 'amazon.co.uk',
						'www.amazon.at', 'amazon.at',
						'www.amazon.ca', 'amazon.ca',
						'www.amazon.it', 'amazon.it',
						'www.amazon.co.jp', 'amazon.co.jp'
					
					);
			if(in_array($link->getHost(), $possibleHosts)){
				$link->setVar('tag', $params->get('amazon_tracking_id'));
			}
		}
		return $link;
	}
}