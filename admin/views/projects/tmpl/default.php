<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
    function resetForm(){
        document.adminForm.search.value='';
        document.adminForm.filter.selectedIndex='';
        document.adminForm.category.selectedIndex='';
        document.adminForm.group_access.selectedIndex='';
    }
</script>

<form action="index.php" method="post" name="adminForm">
    <table class="adminform">
        <tr>
            <td width="100%">
                <?php echo JText::_( 'SEARCH' ).' '.$this->lists['filter']; ?>
                <?php echo JText::_( 'KEYWORD' ); ?> <input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
                <?php echo $this->lists['categories']; ?>&nbsp;
                <?php echo $this->lists['groups']; ?>
                <button onclick="document.adminForm.submit();"><?php echo JText::_( 'GO' ); ?></button>
                <button onclick="resetForm();document.adminForm.submit();"><?php echo JText::_( 'RESET' ); ?></button>
            </td>
            <td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
        </tr>
    </table>
    <table class="adminlist" cellspacing="1">
        <thead>
            <tr>
                <th width="1%">#</th>
                <th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('CATEGORY'), 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('GROUP ACCESS'), 'group_access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('RELEASE #'), 'release_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('JOB #'), 'job_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('TASK #'), 'task_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('WORKORDER #'), 'workorder_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('RELEASE DATE'), 'release_date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="5%"><?php echo JText::_('LOGS'); ?></th>
                <th width="5%"><?php echo JText::_('DOCS'); ?></th>
                <th width="8%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('STATUS'), 'status', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="4%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('APPROVED'), 'approved', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="4%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('PUBLISHED'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="15">
                    <?php echo $this->pageNav->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            if(count($this->rows)){
                $k = 0;
                for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
                    $row = &$this->rows[$i];
                    $link 		= 'index.php?option=com_projectlog&amp;controller=projects&amp;task=edit&amp;cid[]='. $row->id;
                    $log_link   = 'index.php?option=com_projectlog&amp;view=logs&amp;project_id='. $row->id;
                    $doc_link   = 'index.php?option=com_projectlog&amp;view=docs&amp;project_id='. $row->id;
                    $cat_link   = 'index.php?option=com_projectlog&controller=categories&task=edit&cid[]='. $row->category;
                    $group_link = 'index.php?option=com_projectlog&controller=groups&task=edit&cid[]='. $row->group_access;
                    $checked 	= JHTML::_('grid.id', $i, $row->id );
                    $published 	= JHTML::_('grid.published', $row, $i );
                    $approveimg = ($row->approved == 1) ? 'tick.png' : 'publish_x.png';
                    $approvetask = ($row->approved == 1) ? 'disapprove' : 'approve';
                    switch( $row->status ){
                        case JText::_('IN PROGRESS'):
                            $statusclass = 'green';
                        break;
                        case JText::_('ON HOLD'):
                            $statusclass = 'orange';
                        break;
                        case JText::_('COMPLETE'):
                            $statusclass = 'red';
                        break;
                    }
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
                    <td><?php echo $checked;?></td>
                    <td align="left"><a href="<?php echo $link;?>" ><?php echo $row->title; ?></a></td>
                    <td align="center"><?php echo ($row->category) ? '<a href="'.$cat_link.'">'.projectlogHTML::getCatName($row->category).'</a>' : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->group_access) ? '<a href="'.$group_link.'">'.projectlogHTML::getGroupName($row->group_access).'</a>' : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->release_id) ? $row->release_id : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->job_id) ? $row->job_id : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->task_id) ? $row->task_id : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->workorder_id) ? $row->workorder_id : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->release_date) ? $row->release_date : '--'; ?></td>
                    <td align="center"><a href="<?php echo $log_link; ?>"><?php echo JText::_('VIEW'); ?></a></td>
                    <td align="center"><a href="<?php echo $doc_link; ?>"><?php echo JText::_('VIEW'); ?></a></td>
                    <td align="center"><a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i; ?>','changeStatus')" class="<?php echo $statusclass; ?>"><?php echo $row->status; ?></a></td>
                    <td align="center">
                        <a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $approvetask; ?>')">
                            <img src="images/<?php echo $approveimg; ?>" border="0" alt="<?php echo $approvetask; ?>" />
                        </a>
                    </td>
                    <td align="center"><?php echo $published; ?></td>
                </tr>
                <?php $k = 1 - $k; } ?>
            <?php }else{ ?>
                <tr>
                    <td colspan="15" align="center"><?php echo JText::_('NO PROJECTS'); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="view" value="projects" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="projects" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer(); ?></p>