<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.view');

class LinkTrackerViewUrl extends JViewLegacy {
	public function display($tmpl = null){
		JHtml::script('https://www.google.com/jsapi');
		$this->state = $this->get('State');
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($this->getScript());
		JToolBarHelper::title(JText::sprintf('COM_LINKTRACKER_URL_TB_TITLE', $this->get('Url')));
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_LINKTRACKER_URL_BROWSER_TITLE'));
		JSubMenuHelper::addEntry(JText::_('COM_LINKTRACKER_SUBMENU_BACK'),
			'index.php?option=com_linktracker', false);
		JSubMenuHelper::addEntry($this->get('Host'),
			'index.php?option=com_linktracker&view=host&host='.urlencode($this->get('Host')), false);
		parent::display($tmpl);
	}
	public function getScript(){
		$data = $this->get('DiagramData');
		$title = JText::sprintf('COM_LINKTRACKER_URL_CHART_TITLE', $this->get('Url'));
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