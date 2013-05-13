<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.view');

class LinkTrackerViewList extends JViewLegacy {
	public function display($tmpl = null){
		JHtml::script('https://www.google.com/jsapi');
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		
		$this->setupDocument();		

		parent::display($tmpl);
	}
	protected function setupDocument(){
		JToolbarHelper::title(JText::_('COM_LINKTRACKER_LIST_TOOLBARTITLE'));
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_LINKTRACKER_LIST_TOOLBARTITLE'));
		$doc->addScriptDeclaration($this->getScript());
		$user = JFactory::getUser();
		if ($user->authorize('core.admin', 'com_linktracker')) {
			JToolBarHelper::preferences('com_linktracker');

		}
	}
	public function getScript(){
		$data = $this->get('DiagramData');
		$title = JText::_('COM_LINKTRACKER_LIST_CHART_TITLE');
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
		          ['<?php echo JText::_('COM_LINKTRACKER_CHART_HOST');?>', '<?php echo JText::_('COM_LINKTRACKER_CHART_CLICKS');?>']
		          <?php 
		          $i = 0;
		          foreach($this->items as $item){
					if($i > 4) break;
					echo ", ['".$item->domain."', ".$item->count."]";
					$i++;
				  }
		          ?>
		        ]);
		
		        var options = {
		          title: '<?php echo $title;?>'
		        };
		
		        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		        chart.draw(data, options);
		      }
			<?php 
			$script = ob_get_contents();
			ob_end_clean();
			return $script;
		}
}