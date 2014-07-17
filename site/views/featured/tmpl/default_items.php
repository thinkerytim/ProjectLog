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

// Create a shortcut for params.
$params = &$this->item->params;
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_PROJECTLOG_NO_PROJECTS'); ?>	 </p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filters">
	<legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
	<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</fieldset>

	<table class="category">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead><tr>
			<th class="item-num">
				<?php echo JText::_('JGLOBAL_NUM'); ?>
			</th>
			<th class="item-title">
				<?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_PROJECT_EMAIL_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
			</th>
			<?php if ($this->params->get('show_manager_headings')) : ?>
			<th class="item-position">
				<?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_MANAGER', 'a.manager', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_headings')) : ?>
			<th class="item-email">
				<?php echo JText::_('JGLOBAL_EMAIL'); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_task_id_headings')) : ?>
			<th class="item-phone">
				<?php echo JText::_('COM_PROJECTLOG_TELEPHONE'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_mobile_headings')) : ?>
			<th class="item-phone">
				<?php echo JText::_('COM_PROJECTLOG_MOBILE'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_workorder_id_headings')) : ?>
			<th class="item-phone">
				<?php echo JText::_('COM_PROJECTLOG_FAX'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_project_type_headings')) : ?>
			<th class="item-project_type">
				<?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_SUBURB', 'a.project_type', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_state_headings')) : ?>
			<th class="item-client">
				<?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_STATE', 'a.client', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_release_id_headings')) : ?>
			<th class="item-client">
				<?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_COUNTRY', 'a.release_id', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="<?php echo ($i % 2) ? "odd" : "even"; ?>" itemscope itemtype="http://schema.org/Person">
					<td class="item-num">
						<?php echo $i; ?>
					</td>

					<td class="item-title">
						<?php if ($this->items[$i]->published == 0) : ?>
							<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
						<?php endif; ?>
						<a href="<?php echo JRoute::_(ProjectlogHelperRoute::getProjectRoute($item->slug, $item->catid)); ?>" itemprop="url">
							<span itemprop="name"><?php echo $item->name; ?></span>
						</a>
					</td>

					<?php if ($this->params->get('show_manager_headings')) : ?>
						<td class="item-position" itemprop="jobTitle">
							<?php echo $item->manager; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_email_headings')) : ?>
						<td class="item-email" itemprop="email">
							<?php echo $item->email_to; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_task_id_headings')) : ?>
						<td class="item-phone" itemprop="task_id">
							<?php echo $item->task_id; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile_headings')) : ?>
						<td class="item-phone" itemprop="task_id">
							<?php echo $item->mobile; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_workorder_id_headings')) : ?>
						<td class="item-phone" itemprop="workorder_idNumber">
							<?php echo $item->workorder_id; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_project_type_headings')) : ?>
						<td class="item-project_type" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="addressLocality"><?php echo $item->project_type; ?></span>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_client_headings')) : ?>
						<td class="item-client" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="addressRegion"><?php echo $item->client; ?></span>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_release_id_headings')) : ?>
						<td class="item-client" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="addressCountry"><?php echo $item->release_id; ?></span>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

</form>
<?php endif; ?>
