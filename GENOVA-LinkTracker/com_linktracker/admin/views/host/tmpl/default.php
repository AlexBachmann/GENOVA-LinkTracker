<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>
<form action="<?php echo JRoute::_('index.php?option=com_linktracker&view=host');?>" method="post" name="adminForm" id="adminForm">
	<div class="clr"> </div>
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
			<select name="interval" class="inputbox" onchange="this.form.submit()">
				<?php echo JHtml::_('select.options', $this->get('IntervalOptions'), 'value', 'text', $this->state->get('interval'), true);?>
			</select>
			<?php 
				echo JHtml::calendar($this->state->get('period_start'), 'period_start', 'period_start');
				echo JHtml::calendar($this->state->get('period_end'), 'period_end', 'period_end');
			?>
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
		</div>
	</fieldset>
	<div id="chart_div"></div>
	<table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>