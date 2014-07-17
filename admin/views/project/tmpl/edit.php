<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$assoc = JLanguageAssociations::isEnabled();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form')))
		{
			<?php echo $this->form->getField('misc')->save(); ?>
			Joomla.submitform(task, document.getElementById('project-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_PROJECT', true) : JText::_('COM_PROJECTLOG_EDIT_PROJECT', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_DETAILS'); ?></h4>
                        <?php echo $this->form->renderField('client'); ?>
                        <?php echo $this->form->renderField('project_type'); ?>
                        <?php echo $this->form->renderField('release_date'); ?>
						<?php echo $this->form->renderField('image'); ?>
                    
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_CREW'); ?></h4>
						<?php echo $this->form->renderField('manager'); ?>
                        <?php echo $this->form->renderField('chief'); ?>
                        <?php echo $this->form->renderField('technicians'); ?>
                        <?php echo $this->form->renderField('onsite'); ?>
                    
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_DATES'); ?></h4>
                        <?php echo $this->form->renderField('deployment_from'); ?>
                        <?php echo $this->form->renderField('deployment_to'); ?>
                        <?php echo $this->form->renderField('contract_from'); ?>
                        <?php echo $this->form->renderField('contract_to'); ?>                        					
					</div>
					<div class="span6">
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_LOC'); ?></h4>                        
                        <?php echo $this->form->renderField('general_loc'); ?>
                        <?php echo $this->form->renderField('specific_loc'); ?>
						
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_IDENTIFIERS'); ?></h4>       
                        <?php echo $this->form->renderField('release_id'); ?>
                        <?php echo $this->form->renderField('job_id'); ?>
                        <?php echo $this->form->renderField('workorder_id'); ?>
                        <?php echo $this->form->renderField('task_id'); ?>
                        
						<h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_CONTACT'); ?></h4>   
                        <?php echo $this->form->renderField('email_to'); ?>
                        <?php echo $this->form->renderField('mobile'); ?>						
						<?php echo $this->form->renderField('webpage'); ?>
                        
						<h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_FILTERS'); ?></h4>   
                        <?php echo $this->form->renderField('sortname1'); ?>
						<?php echo $this->form->renderField('sortname2'); ?>
						<?php echo $this->form->renderField('sortname3'); ?>
					</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'misc', JText::_('JGLOBAL_FIELDSET_MISCELLANEOUS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
				<div class="form-vertical">
					<?php echo $this->form->renderField('misc'); ?>
				</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php if ($assoc) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
