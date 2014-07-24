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

$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form')))
		{
			<?php echo $this->form->getField('misc')->save(); ?>

			if (window.opener && (task == 'project.save' || task == 'project.cancel'))
			{
				window.opener.document.closeEditWindow = self;
				window.opener.setTimeout('window.document.closeEditWindow.close()', 1000);
			}

			Joomla.submitform(task, document.getElementById('project-form'));
		}
	}
</script>
<div class="container-popup">

<div class="pull-right">
	<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('project.apply');"><?php echo JText::_('JTOOLBAR_APPLY') ?></button>
	<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('project.save');"><?php echo JText::_('JTOOLBAR_SAVE') ?></button>
	<button class="btn" type="button" onclick="Joomla.submitbutton('project.cancel');"><?php echo JText::_('JCANCEL') ?></button>
</div>

<div class="clearfix"> </div>
<hr class="hr-condensed" />

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&layout=modal&tmpl=component&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate form-horizontal">
    <div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_PROJECT', true) : JText::_('COM_PROJECTLOG_EDIT_PROJECT', true)); ?>
		<div class="row-fluid">
			<div class="span9">
                <div class="row-fluid form-horizontal-desktop">
                    <?php echo $this->form->renderField('name'); ?>
                </div>
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_DETAILS'); ?></h3>                        
                        <?php echo $this->form->renderField('client'); ?>
                        <?php echo $this->form->renderField('project_type'); ?>
                        <?php echo $this->form->renderField('release_date'); ?>
                        <?php echo $this->form->renderField('status'); ?>
						<?php echo $this->form->renderField('image'); ?>
                    </div>
                    <div class="span6">
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_IDENTIFIERS'); ?></h3>       
                        <?php echo $this->form->renderField('release_id'); ?>
                        <?php echo $this->form->renderField('job_id'); ?>
                        <?php echo $this->form->renderField('workorder_id'); ?>
                        <?php echo $this->form->renderField('task_id'); ?>
                    </div>
                </div>
                <hr />
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">                  
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_DATES'); ?></h3>
                        <?php echo $this->form->renderField('deployment_from'); ?>
                        <?php echo $this->form->renderField('deployment_to'); ?>
                        <?php echo $this->form->renderField('contract_from'); ?>
                        <?php echo $this->form->renderField('contract_to'); ?>                        					
					</div>
					<div class="span6">
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_LOC'); ?></h3>                        
                        <?php echo $this->form->renderField('general_loc'); ?>
                        <?php echo $this->form->renderField('specific_loc'); ?>
                    </div>
                </div>
                <hr />
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">        
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_CREW'); ?></h3>
						<?php echo $this->form->renderField('manager'); ?>
                        <?php echo $this->form->renderField('chief'); ?>
                        <?php echo $this->form->renderField('technicians'); ?>
                        <?php echo $this->form->renderField('onsite'); ?>
                    </div>
                    <div class="span6">                        
						<h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_CONTACT'); ?></h3>   
                        <?php echo $this->form->renderField('email_to'); ?>
                        <?php echo $this->form->renderField('mobile'); ?>						
						<?php echo $this->form->renderField('webpage'); ?>
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
			<div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
