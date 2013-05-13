<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_LINKTRACKER_LIST_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_LINKTRACKER_LIST_DOMAIN', 'domain', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_( 'COM_LINKTRACKER_LIST_CLICKS'); ?>
	</th>
</tr>