<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');

$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$colspan    = 11;
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&view=projects'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search" class="inputbox" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                    <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                    <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                </select>
            </div>
            <div class="btn-group pull-right">
                <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                </select>
            </div>
        </div>
        <div class="clearfix"> </div>

        <table class="table table-striped" id="employeeList">
            <thead>
                <tr>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>
                    <th width="12%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_TITLE', 'title', $listDirn, $listOrder); ?></th>
                    <th width="12%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_CATEGORY', 'category', $listDirn, $listOrder); ?></th>
                    <th width="12%"><?php echo JHTML::_('grid.sort',  'COM_PROJECTLOG_GROUP_ACCESS', 'group_access', $listDirn, $listOrder ); ?></th>
                    <th width="15%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_RELEASE_NUM', 'release_id', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_JOB_NUM', 'job_id', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_TASK_NUM', 'task_id', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_WORKORDER_NUM', 'workorder_id', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort',  'COM_PROJECTLOG_RELEASE_DATE', 'release_date', $listDirn, $listOrder); ?></th>
                    <th width="5%"><?php echo JText::_('COM_PROJECTLOG_LOGS'); ?></th>
                    <th width="5%"><?php echo JText::_('COM_PROJECTLOG_DOCS'); ?></th>
                    <th width="4%"><?php echo JHtml::_('grid.sort', JText::_('COM_PROJECTLOG_PUBLISHED'), 'published', $listDirn, $listOrder ); ?></th>                
                    <th width="4%"><?php echo JHtml::_('grid.sort', JText::_('COM_PROJECTLOG_APPROVED'), 'approved', $listDirn, $listOrder ); ?></th>
                    <th width="8%"><?php echo JHtml::_('grid.sort', JText::_('COM_PROJECTLOG_STATUS'), 'status', $listDirn, $listOrder ); ?></th>                
                    <th width="1%" class="nowrap"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            
            <tfoot>
                <tr>
                    <td colspan="12"><?php echo $this->pagination->getListFooter(); ?>
                </tr>
            </tfoot>
            
            <tbody>
                <?php 
                if(count($this->items) > 0):
                    foreach ($this->items as $i => $item) :
                        $link 		= 'index.php?option=com_projectlog&task=projects.edit&id='.$item->id;
                        $log_link   = 'index.php?option=com_projectlog&view=logs&project_id='.$item->id;
                        $doc_link   = 'index.php?option=com_projectlog&view=docs&project_id='.$item->id;
                        $cat_link   = 'index.php?option=com_projectlog&task=categories.edit&id='.$item->category;
                        $group_link = 'index.php?option=com_projectlog&task=groups.edit&id='.$item->group_access;

                        // joomla stuff
                        $ordering       = ($listOrder == 'ordering');
                        $canCreate      = $user->authorise('core.create',		'com_projectlog.project.'.$item->id);
                        $canEdit        = $user->authorise('core.edit',			'com_projectlog.project.'.$item->id);
                        $canChange      = $user->authorise('core.edit.state',	'com_projectlog.project.'.$item->id);
                        ?>                
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td>
                                <?php if ($canEdit) : ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_projectlog&task=project.edit&id='.(int) $item->id); ?>">
                                        <?php echo $this->escape($item->title); ?></a>
                                <?php else : ?>
                                    <?php echo $this->escape($item->title); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo ($item->category) ? '<a href="'.$cat_link.'">'.projectlogHTML::getCatName($item->category).'</a>' : '--'; ?></td>
                            <td class="center"><?php echo ($item->group_access) ? '<a href="'.$group_link.'">'.projectlogHTML::getGroupName($item->group_access).'</a>' : '--'; ?></td>
                            <td class="center"><?php echo ($item->release_id) ? $item->release_id : '--'; ?></td>
                            <td class="center"><?php echo ($item->job_id) ? $item->job_id : '--'; ?></td>
                            <td class="center"><?php echo ($item->task_id) ? $item->task_id : '--'; ?></td>
                            <td class="center"><?php echo ($item->workorder_id) ? $item->workorder_id : '--'; ?>&nbsp;</td>
                            <td class="center"><?php echo ($item->release_date) ? $item->release_date : '--'; ?></td>
                            <td align="center"><a href="<?php echo $log_link; ?>"><?php echo JText::_('COM_PROJECTLOG_VIEW'); ?></a></td>
                            <td align="center"><a href="<?php echo $doc_link; ?>"><?php echo JText::_('COM_PROJECTLOG_VIEW'); ?></a></td>
                            <td class="center">
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'employees.', $canChange, 'cb'); ?>
                            </td>
                            <td class="center">
                                <?php echo JHtml::_('pladministrator.approve', $item->approved, $i, $canChange); ?>
                            </td>
                            <td class="center">
                                <?php echo JHtml::_('pladministrator.status', $item->status, $i, $canChange); ?>
                            </td>
                            <td class="center">
                                <?php echo $item->id; ?>
                            </td>
                        </tr>
                        <?php 
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="15" class="center">
                            <?php echo JText::_('COM_PROJECTLOG_NO_RESULTS'); ?>
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
		<?php //Load the batch processing form. ?>
		<?php //echo $this->loadTemplate('batch'); ?>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<?php echo projectlogAdmin::footer( ); ?>