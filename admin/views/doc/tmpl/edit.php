<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$plparams = JComponentHelper::getParams('com_projectlog');

$app = JFactory::getApplication();
$assoc = JLanguageAssociations::isEnabled();

$allowed_types_desc = sprintf(JText::_('COM_PROJECTLOG_ALLOWED_FILETYPES'), $plparams->get('allowed_filetypes'));
$this->form->setFieldAttribute('path', 'description', $allowed_types_desc);
$this->form->setFieldAttribute('pl_document', 'accept', trim($plparams->get('allowed_mimetypes', 'image/*')));
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'doc.cancel' || document.formvalidator.isValid(document.id('doc-form')))
		{
			Joomla.submitform(task, document.getElementById('doc-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="doc-form" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_DOC', true) : JText::_('COM_PROJECTLOG_EDIT_DOC', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
                    <?php echo $this->form->renderField('project_id'); ?>
                    <?php if($this->item->id && $this->item->path): ?>
                        <div class="alert alert-info"><h3><?php echo JText::_('NOTICE'); ?></h3><p><?php echo sprintf(JText::_('COM_PROJECTLOG_DOC_UPLOAD_NOTE'), $this->item->path); ?></p></div>
                        <?php echo $this->form->renderField('path'); ?>
                    <?php endif; ?>
                    <div class="alert alert-warning"><?php echo $allowed_types_desc; ?></div>
                    <?php echo $this->form->renderField('pl_document'); ?>
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

	