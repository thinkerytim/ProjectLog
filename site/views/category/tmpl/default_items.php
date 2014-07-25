<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.framework');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items)) : ?>
	<div class="alert alert-info center"> <?php echo JText::_('COM_PROJECTLOG_NO_PROJECTS'); ?></div>
<?php else : ?>

	<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search"><span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span><?php echo JText::_('COM_PROJECTLOG_FILTER_LABEL').'&#160;'; ?></label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_PROJECTLOG_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_PROJECTLOG_FILTER_SEARCH_DESC'); ?>" />
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>
	</fieldset>
	<?php endif; ?>

		<table class="table table-striped category">
            <thead>
                <tr>
                    <th><?php echo JText::_('COM_PROJECTLOG_PROJECT_NAME'); ?></th>
                    <?php if ($this->params->get('show_release_id_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_RELEASE_NUM'); ?></th>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_workorder_id_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_WORKORDER_NUM'); ?></th>
                    <?php endif; ?>                            
                    <?php if ($this->params->get('show_task_id_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_TASK_NUM'); ?></th>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_client_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_CLIENT'); ?></th>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_project_type_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_PROJECT_TYPE'); ?></th>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_manager_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_MANAGER'); ?></th>
                    <?php endif; ?>
                    <?php if ($this->params->get('show_mobile_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_MOBILE'); ?></th>
                    <?php endif; ?>                        
                    <?php if ($this->params->get('show_email_headings')) : ?>
                        <th><?php echo JText::_('COM_PROJECTLOG_EMAIL_LABEL'); ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tfoot>
                <tr colspan="9">
                    <?php if ($this->params->get('show_pagination')) : ?>
                    <div class="pagination">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                        <p class="counter">
                            <?php echo $this->pagination->getPagesCounter(); ?>
                        </p>
                        <?php endif; ?>
                        <?php echo $this->pagination->getPagesLinks(); ?>
                    </div>
                    <?php endif; ?>
                </tr>
            </tfoot>
            <tbody>
			<?php foreach ($this->items as $i => $item) : ?>            
				<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
					<?php if ($this->items[$i]->published == 0) : ?>
						<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
					<?php else: ?>
						<tr class="cat-list-row<?php echo $i % 2; ?>" >
					<?php endif; ?>
                            <td>
                                <div class="list-title">
                                    <a href="<?php echo JRoute::_(ProjectlogHelperRoute::getProjectRoute($item->slug, $item->catid)); ?>">
                                        <?php echo $item->name; ?></a>
                                    <?php if ($this->items[$i]->published == 0) : ?>
                                        &nbsp;<span class="label label-warning hasTooltip" title="<?php echo JText::_('JUNPUBLISHED'); ?>"><span class="icon-thumbs-down"></span></span>
                                    <?php elseif ($this->items[$i]->published == -2) : ?>
                                        &nbsp;<span class="label label-important hasTooltip" title="<?php echo JText::_('JTRASHED'); ?>"><span class="icon-trash"></span></span>
                                    <?php else: ?>
                                        <?php if ($this->items[$i]->featured) : ?>
                                            &nbsp;<span class="label label-success hasTooltip" title="<?php echo JText::_('JFEATURED'); ?>"><span class="icon-star"></span></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php if ($this->params->get('show_release_id_headings')) : ?>
                            <td><?php echo ($item->params->get('show_release_id') AND !empty($item->release_id)) ? $item->release_id : '--'; ?></td>
						<?php endif; ?>
                        <?php if ($this->params->get('show_workorder_id_headings')) : ?>
                            <td><?php echo ($item->params->get('show_workorder_id') AND !empty($item->workorder_id)) ? $item->workorder_id : '--'; ?></td>
                        <?php endif; ?>                            
                        <?php if ($this->params->get('show_task_id_headings')) : ?>
                            <td><?php echo ($item->params->get('show_task_id') AND !empty($item->task_id)) ? $item->task_id : '--'; ?></td>
                        <?php endif; ?>
                        <?php if ($this->params->get('show_client_headings')) : ?>
                            <td><?php echo ($item->params->get('show_client') AND !empty($item->client)) ? $item->client : '--'; ?></td>
						<?php endif; ?>
                        <?php if ($this->params->get('show_project_type_headings')) : ?>
                            <td><?php echo ($item->params->get('show_project_type') AND !empty($item->project_type)) ? $item->project_type : '--'; ?></td>
						<?php endif; ?>
                        <?php if ($this->params->get('show_manager_headings')) : ?>
                            <td><?php echo ($item->params->get('show_manager') AND !empty($item->manager)) ? $item->manager : '--'; ?></td>
						<?php endif; ?>
                        <?php if ($this->params->get('show_mobile_headings')) : ?>
                            <td><?php echo ($item->params->get('show_mobile') AND !empty($item->mobile)) ? $item->mobile : '--'; ?></td>
                        <?php endif; ?>                        
                        <?php if ($this->params->get('show_email_headings')) : ?>
                            <td><?php echo ($item->params->get('show_email') AND !empty($item->email_to)) ? $item->email_to : '--'; ?></td>
						<?php endif; ?>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
            </tbody>
		</table>

		
		<div>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		</div>
</form>
<?php endif; ?>
