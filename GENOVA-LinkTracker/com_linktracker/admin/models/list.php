<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.modellist');

class LinkTrackerModelList extends JModelList {
	public function __construct($config){
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'id',
					'domain',
					'url'
			);
		}
		parent::__construct($config);
		$this->context = $this->option;
	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '');
		$this->setState('filter.search', $search);
		
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
		parent::populateState('count', 'desc');
	}
	/*protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('l.*, COUNT(l.*) as count')->from('#__lt_links as l.');
		$query->group('l.domain');
		if($this->getState('filder.search')){
			$query->where('domain = '.$db->quote($this->getState('filter.search')));
		}
		$orderCol	= $this->state->get('list.ordering', 'count');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}*/
	public function getListQuery(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('l.id as id, l.domain as domain, COUNT(*) as count');
		$query->group('l.domain');
		$query->from('#__lt_clicks as c');
		$query->join('INNER', '#__lt_links as l ON c.url_id = l.id');
		$query->where('c.time >= '.$db->quote($this->getState('period_start').' 00:00:00'));
		$query->where('c.time <= '.$db->quote($this->getState('period_end').' 23:59:59'));
		$query->order('count DESC');
		return $query;
	}
	public function getIntervalOptions(){
		return array(
				array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_DAY'), 'value' => 'day'),
				array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_WEEK'), 'value' => 'week'),
				array('text' => JText::_('COM_LINKTRACKER_CHART_INTERVAL_MONTH'), 'value' => 'month')
		);
	}
	
}