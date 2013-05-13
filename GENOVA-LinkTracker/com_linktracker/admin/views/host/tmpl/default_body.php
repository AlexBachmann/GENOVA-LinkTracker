<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

foreach($this->items as $i => $item){
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="center">
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="index.php?option=com_linktracker&view=url&id=<?php echo $item->id;?>"><?php echo $item->path.$item->query;?></a>
		</td>
		<td class="center">
			<?php echo $item->count;?>
		</td>
	</tr>
	<?php
}