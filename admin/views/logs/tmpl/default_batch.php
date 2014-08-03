<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$published = $this->state->get('filter.published');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('COM_PROJECTLOG_BATCH_LOG_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo JText::_('COM_PROJECTLOG_BATCH_LOG_TIP'); ?></p>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('batch.language'); ?>
				</div>
			</div>
            <?php if ($published >= 0) : ?>
				<div class="control-group">
					<div class="controls">
                        <label><?php echo JText::_('COM_PROJECTLOG_FIELD_PROJECT_LABEL'); ?></label>
						<?php echo JHtml::_('project.batchproject'); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-project-id').value='';document.id('batch-language-id').value='';" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('log.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
