<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.view');

class LinkTrackerViewHost extends JViewLegacy {
	public function display($tmpl = null){
		JHtml::script('https://www.google.com/jsapi');
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($this->getScript());
		JToolBarHelper::title(JText::sprintf('COM_LINKTRACKER_HOST_TB_TITLE', $this->state->get('host')));
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_LINKTRACKER_HOST_BROWSER_TITLE'));
		JSubMenuHelper::addEntry(JText::_('COM_LINKTRACKER_SUBMENU_BACK'),
			'index.php?option=com_linktracker', false);
		parent::display($tmpl);
	}
	public function getScript(){
		$data = $this->get('DiagramData');
		$title = JText::sprintf('COM_LINKTRACKER_HOST_CHART_TITLE', $this->state->get('host'));
		switch($this->state->get('interval')){
			case 'month':
				$label = JText::_('COM_LINKTRACKER_CHART_LABEL_MONTH');
				break;
			case 'week':
				$label = JText::_('COM_LINKTRACKER_CHART_LABEL_WEEK');
				break;
			default:
				$label = JText::_('COM_LINKTRACKER_CHART_LABEL_DAY');
				break;
		}
		ob_start();
		?>
		  google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	          ['<?php echo $label;?>', '<?php echo JText::_('COM_LINKTRACKER_CHART_CLICKS');?>']
	          <?php 
	          foreach($data as $item){
				echo ", ['".$item->x."', ".$item->y."]";
			  }
	          ?>
	        ]);
	
	        var options = {
	          title: '<?php echo $title;?>'
	        };
	
	        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	        chart.draw(data, options);
	      }
		<?php 
		$script = ob_get_contents();
		ob_end_clean();
		return $script;
	}
}