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
JHtml::_('behavior.modal', 'a.modal_jform_contenthistory');

// Create shortcut to parameters.
$params = $this->state->get('params');
if($this->item && $this->item->catid) $this->state->set('project.catid', $this->item->catid);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'projectform.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			<?php echo $this->form->getField('misc')->save(); ?>
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
    
    <?php if(!$this->item->id && $this->params->get('require_approval', 1)): ?>
    <div class="alert alert-info">
        <?php echo JText::_('COM_PROJECTLOG_APPROVAL_REQUIRED_NOTICE'); ?>
    </div>
    <?php elseif($this->item->id && $this->params->get('moderate_projects', 1)): ?>
    <div class="alert alert-info">
        <?php echo JText::_('COM_PROJECTLOG_MODERATION_NOTICE'); ?>
    </div>
    <?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_projectlog&a_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('projectform.save')">
					<span class="icon-ok"></span>&#160;<?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('projectform.cancel')">
					<span class="icon-cancel"></span>&#160;<?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
			<?php if ($params->get('save_history', 0)) : ?>
			<div class="btn-group">
				<?php echo $this->form->getInput('projecthistory'); ?>
			</div>
			<?php endif; ?>
		</div>
        <?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_PROJECT', true) : JText::_('COM_PROJECTLOG_EDIT_PROJECT', true)); ?>

                <div class="row-fluid">
                    <div class="span6">
                        <h3><?php echo JText::_('COM_PROJECTLOG_PROJECT_DETAILS'); ?></h3>
                        <?php if (!$this->params->get('enable_category', 0) == 1 && !$this->state->get('project.catid')) :?>
                            <?php echo $this->form->renderField('catid'); ?>
                        <?php endif; ?>
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
                <div class="row-fluid">
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
                <div class="row-fluid">
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
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('JGLOBAL_FIELDSET_MISCELLANEOUS', true)); ?>
                <?php echo $this->form->renderField('misc'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->form->renderField('tags'); ?>
                        <?php if ($params->get('save_history', 0)) : ?>
                            <?php echo $this->form->renderField('version_note'); ?>
                        <?php endif; ?>
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
        <?php if ($this->params->get('enable_category', 0) == 1 || $this->state->get('project.catid')) :?>
            <input type="hidden" name="jform[catid]" value="<?php echo $this->state->get('project.catid'); ?>" />
        <?php endif; ?>
        <?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();  ?>
