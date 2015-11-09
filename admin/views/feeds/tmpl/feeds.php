<?php defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.tooltip');
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

?>
<?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
            <thead>
                <tr>
                    <th width="5">#</th>
                    <th width="1%" class="hidden-phone"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
                    <th width="100" align="left" nowrap="nowrap"><?php echo JText::_( 'Name' ); ?></th>
                    <th width="25" align="center" nowrap="nowrap"><?php echo JText::_( 'Button' ); ?></th>
                    <th width="50" align="left" nowrap="nowrap"><?php echo JText::_( 'Type' ); ?></th>
                    <th width="200" align="left" nowrap="nowrap"><?php echo JText::_( 'Feed url' ); ?></th>
                    <th width="5%" nowrap="nowrap"><?php echo JText::_( 'Published' ); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="9">
                        <?php echo $this->pagination->getListFooter(); ?>

                    </td>
                </tr>
            </tfoot>
            <?php
            $k = 0;
            for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
                $row = $this->items[$i];

                $checked 	= JHTML::_('grid.id',  $i, $row->id );
                $published 	= JHTML::_('grid.published', $row, $i );

                //$feedurl = JURI::root() . JRoute::_( "index.php?option=com_sdrsssyndicator&feed_id=".$row->id."&format=raw");
                $feedurl =   JURI::root() ."index.php?option=com_sdrsssyndicator&feed_id=".$row->id."&format=raw";
                ?>
            <tr class="<?php echo "row$k"; ?>">
                <td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
                <td>
                    <?php echo $checked ;?>
                </td>
                <td>

                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit feed' );?>::<?php echo $this->escape($row->feed_name); ?>">
                        <a href="#" onclick="return listItemTask('cb<?php echo $i; ?>','edit')">
                    <?php echo $this->escape($row->feed_name); ?></a></span>

                </td>
                <td><img src="<?php if($row->feed_button != "") {echo (JURI::root() . "components/com_sdrsssyndicator/assets/images/buttons/".$row->feed_button);} ?>"></td>
                <td><?php echo $row->feed_type; ?></td>
                <td><a href="<?php echo $feedurl;?>" target="_blank"><?php echo $feedurl;?></a></td>
                <td><?php echo $published ;?></td>
            <?php		$k = 1 - $k; ?>		</tr>
            <?php	}
        ?>

        </table>
    </div>

    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="option" value="com_sdrsssyndicator" />
    <input type="hidden" name="task" value="feeds" />
    <input type="hidden" name="controller" value="feed" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>