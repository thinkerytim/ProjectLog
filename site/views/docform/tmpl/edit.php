<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tabstate');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Create shortcut to parameters.
$params = $this->state->get('params');
$plparams = JComponentHelper::getParams('com_projectlog');

$allowed_types_desc = sprintf(JText::_('COM_PROJECTLOG_ALLOWED_FILETYPES'), $plparams->get('allowed_filetypes'));
$this->form->setFieldAttribute('path', 'description', $allowed_types_desc);
$this->form->setFieldAttribute('pl_document', 'accept', trim($plparams->get('allowed_mimetypes', 'image/*')));
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'docform.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task);
		}
	}
</script>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_projectlog&a_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical" enctype="multipart/form-data">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('docform.save')">
					<span class="icon-ok"></span>&#160;<?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('docform.cancel')">
					<span class="icon-cancel"></span>&#160;<?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
		</div>
        <?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_DOC', true) : JText::_('COM_PROJECTLOG_EDIT_DOC', true)); ?>

                <div class="row-fluid">
                    <div class="span7">
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
                    <div class="span5">
                        <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                    </div>
                </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php echo $this->form->renderField('created_by_alias'); ?>
                        <?php if ($this->item->params->get('access-change')) : ?>
                            <?php echo $this->form->renderField('published'); ?>
                            <?php echo $this->form->renderField('featured'); ?>
                            <?php echo $this->form->renderField('publish_up'); ?>
                            <?php echo $this->form->renderField('publish_down'); ?>
                        <?php endif; ?>
                        <?php echo $this->form->renderField('access'); ?>
                        <?php if (is_null($this->item->id)):?>
                            <div class="control-group">
                                <div class="control-label">
                                </div>
                                <div class="controls">
                                    <?php echo JText::_('COM_PROJECTLOG_ORDERING'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php echo $this->form->renderField('language'); ?>
                        <?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
                    </div>
                </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>  
        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
        <?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();  ?>