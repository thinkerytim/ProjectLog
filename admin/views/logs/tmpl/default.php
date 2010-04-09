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
        document.adminForm.project_id.selectedIndex='';
    }
</script>

<form action="index.php" method="post" name="adminForm">
    <table class="adminform">
        <tr>
            <td width="100%">
                <?php echo JText::_( 'SEARCH' ).' '.$this->lists['filter']; ?>
                <?php echo JText::_( 'KEYWORD' ); ?> <input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" /> &nbsp;
                <?php echo $this->lists['projects']; ?>
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
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('PROJECT'), 'project_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('LOG DATE'), 'date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('LOGGED BY'), 'loggedby', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('MOD DATE'), 'modified', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('MODIFIED BY'), 'modified_by', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th width="4%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('PUBLISHED'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="9">
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
                    $link 		= 'index.php?option=com_projectlog&amp;controller=logs&amp;task=edit&amp;cid[]='. $row->id;
                    $project_link = 'index.php?option=com_projectlog&amp;controller=projects&amp;task=edit&amp;cid[]='. $row->project_id;
                    $checked 	= JHTML::_('grid.id', $i, $row->id );
                    $published 	= JHTML::_('grid.published', $row, $i );
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
                    <td><?php echo $checked;?></td>
                    <td align="left"><a href="<?php echo $link;?>" ><?php echo $row->title; ?></a></td>
                    <td align="center"><?php echo ($row->project_id) ? '<a href="'.$project_link.'">'.projectlogHTML::getProjectName($row->project_id).'</a>' : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->date) ? $row->date : '--'; ?>&nbsp;</td>                    
                    <td align="center"><?php echo ($row->loggedby) ? projectlogHTML::getUserName($row->loggedby) : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->modified) ? $row->modified : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo ($row->modified_by) ? projectlogHTML::getUserName($row->modified_by) : '--'; ?>&nbsp;</td>
                    <td align="center"><?php echo $published; ?></td>
                </tr>
                <?php $k = 1 - $k; } ?>
            <?php }else{ ?>
                <tr>
                    <td colspan="9" align="center"><?php echo JText::_('NO LOGS'); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="view" value="logs" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="logs" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer(); ?></p>