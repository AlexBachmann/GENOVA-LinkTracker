<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.modellist');

class LinkTrackerModelHost extends JModelList {
	public function __construct($config){
		parent::__construct($config);
		$this->context = $this->option;
	}
	protected function populateState($ordering = null, $direction = null){
		$host = $this->getUserStateFromRequest($this->context.'.host', 'host', '');
		if(!$host){
			throw new RuntimeException('No host has been passed in the request');
		}
		if(substr($host, 0, 4) == 'www.'){
			$host = substr($host, 4);
		}
		$this->setState('host', $host);
		
		$end = $this->getUserStateFromRequest($this->context.'.period_end', 'period_end', '');
		if(!$end){
			$endDate = JFactory::getDate();
			$end = $endDate->format('Y-m-d');
		}
		$this->setState('period_end', $end);
		
		$start = $this->getUserStateFromRequest($this->context.'.period_start', 'period_start', '');
		if(!$start){
			$endDate = JFactory::getDate($end);
			//substract 30 days from the end date
			$endDate->modify('-30 days');
			$start = $endDate->format('Y-m-d');
		}else{
			//Check if start is after end
			$startDate = JFactory::getDate($start);
			$endDate = JFactory::getDate($end);
			if($startDate->format('U') > $endDate->format('U')){
				$start = $end;
			}
		}
		$this->setState('period_start', $start);
		
		$interval = $this->getUserStateFromRequest($this->context.'.interval', 'interval', 'day');
		$this->setState('interval', $interval);
	
		// List state information.
		parent::populateState();
	}
	public function getDiagramData(){
		static $data = null;
		if(!$data){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			switch($this->getState('interval')){
				case 'month':
					$query->select("DATE_FORMAT(c.time,'%Y-%m') as intrvl");
					$query->select("DATE_FORMAT(c.time,'%m') as x, COUNT(*) as y");
					$interval = '+1 month';
					break;
				case 'week':
					$query->select("DATE_FORMAT(c.time,'%Y-%v') as intrvl");
					$query->select("DATE_FORMAT(c.time,'%v') as x, COUNT(*) as y");
					$interval = '+1 week';
					break;
				default:
					$query->select("DATE(c.time) as intrvl");
					$query->select("DATE_FORMAT(c.time,'%d') as x, COUNT(*) as y");
					$interval = '+1 day';
					break;
			}
			$query->group('intrvl');
			$query->from('#__lt_clicks as c');
			$query->join('INNER', '#__lt_links as l ON c.url_id = l.id');
			$query->where('(l.domain = '.$db->quote($this->getState('host')).' OR l.domain = '.$db->quote('www.'.$this->getState('host')).')');
			$query->where('c.time >= '.$db->quote($this->getState('period_start').' 00:00:00'));
			$query->where('c.time <= '.$db->quote($this->getState('period_end').' 23:59:59'));
			$query->order('c.time ASC');
			$string = (string)$query;
			$db->setQuery($query);
			$data = $db->loadObjectList('intrvl');
			
			//We now have to fill in the missing dates (the ones, where we have no records for)
			$start = JFactory::getDate($this->getState('period_start'));
			$end = JFactory::getDate($this->getState('period_end'));
			$results = array();
			while(true){
				switch($this->getState('interval')){
					case 'month':
						$startInterval = $start->format('Y-m');
						$endInterval = $end->format('Y-m');
						$x = $start->format('m');	
						break;
					case 'week':
						$startInterval = $start->format('Y-W');
						$endInterval = $end->format('Y-W');	
						$x = $start->format('W');
						break;
					default:
						$startInterval = $start->format('Y-m-d');
						$endInterval = $end->format('Y-m-d');
						$x = $start->format('d');
						break;
				}
				if($startInterval > $endInterval) break;
				if(isset($data[$startInterval])){
					$results[$startInterval] = $data[$startInterval];
				}else{
					$result = new stdClass();
					$result->intrvl = $startInterval;
					$result->x = $x;
					$result->y = 0;
					$results[$startInterval] = $result;
				}
				$start->modify($interval);
			}
		}
		return $results;
	}
	public function getIntervalOptions(){
		return array(
					array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_DAY'), 'value' => 'day'),
					array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_WEEK'), 'value' => 'week'),
					array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_MONTH'), 'value' => 'month')
				);
	}
	public function getListQuery(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('l.*, COUNT(*) as count');
		$query->group('c.url_id');
		$query->from('#__lt_clicks as c');
		$query->join('INNER', '#__lt_links as l ON c.url_id = l.id');
		$query->where('(l.domain = '.$db->quote($this->getState('host')).' OR l.domain = '.$db->quote('www.'.$this->getState('host')).')');
		$query->where('c.time >= '.$db->quote($this->getState('period_start').' 00:00:00'));
		$query->where('c.time <= '.$db->quote($this->getState('period_end').' 23:59:59'));
		$query->order('count DESC');
		return $query;
	}
	public function getTotal(){
		static $total = null;
		if(is_null($total)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)');
			$query->from('#__lt_clicks as c');
			$query->join('INNER', '#__lt_links as l ON c.url_id = l.id');
			$query->where('(l.domain = '.$db->quote($this->getState('host')).' OR l.domain = '.$db->quote('www.'.$this->getState('host')).')');
			$query->where('c.time >= '.$db->quote($this->getState('period_start').' 00:00:00'));
			$query->where('c.time <= '.$db->quote($this->getState('period_end').' 23:59:59'));
			$db->setQuery($query);
			$total = $db->loadResult();
		}
		return $total;
	}
}