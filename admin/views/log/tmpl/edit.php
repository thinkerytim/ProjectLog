<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
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
		if (task == 'log.cancel' || document.formvalidator.isValid(document.id('log-form')))
		{
			Joomla.submitform(task, document.getElementById('log-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="log-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_LOG', true) : JText::_('COM_PROJECTLOG_EDIT_LOG', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
                    <?php echo $this->form->renderField('project_id'); ?>
                    <?php echo $this->form->renderField('workorder_id'); ?>
                    <?php echo $this->form->renderField('description'); ?>
                </div>                
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>        

		<?php if ($assoc) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>      

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
    <?php echo projectlogHTML::buildThinkeryFooter(); ?>
</form>
