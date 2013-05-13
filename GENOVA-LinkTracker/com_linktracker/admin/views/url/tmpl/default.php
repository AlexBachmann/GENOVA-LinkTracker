<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>
<form action="<?php echo JRoute::_('index.php?option=com_linktracker&view=url');?>" method="post" name="adminForm" id="adminForm">
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
	<p style="text-align: center; margin-top: 15px;">
		<a href="<?php echo $this->get('Url');?>" target="_blank"><?php echo $this->get('Url');?></a><br />
		<?php echo JText::sprintf('COM_LINKTRACKER_CHART_TOTAL', $this->get('Total'));?>
	</p>
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>