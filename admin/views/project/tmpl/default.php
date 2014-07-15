<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
JHTMLBehavior::formvalidation();
?>

<script language="javascript" type="text/javascript">
    function submitbutton(task)
	{
		var form = document.adminForm;
        var desc = <?php echo $this->editor->getContent( 'description' ); ?>

		if (task == 'cancel') {
			submitform( task );
		}  
		else {
			
			if (!document.formvalidator.isValid(form)) {
                //form.check.value='<?php echo JUtility::getToken(); ?>';//send token
                alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
                return false;
            }else if(form.release_date.value == ''){
                alert( '<?php echo JText::_('ENTER RELEASE DATE'); ?>' );
                return false;
            }else if(form.manager.selectedIndex == ''){
                alert( '<?php echo JText::_('ENTER MANAGER'); ?>' );
                return false;
            }else if(desc == ''){
                alert( '<?php echo JText::_('ENTER DESCRIPTION'); ?>');
                return false;
            }
            <?php echo $this->editor->save( 'description' ); ?>
			submitform( task );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table width="100%">
        <tr>
            <td width="70%">
                <table class="admintable" width="100%">
                    <tr>
                        <td width="20%" class="key"><?php echo JText::_('TITLE'); ?>*</td>
                        <td width="80%" valign="top">
                            <input class="inputbox required" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->project->title); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('CATEGORY'); ?>  :: <?PHP echo JText::_('CATEGORY TIP'); ?>"><?php echo JText::_('CATEGORY'); ?></span></td>
                        <td><?php echo $this->lists['categories']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('GROUP ACCESS'); ?>  :: <?PHP echo JText::_('GROUP ACCESS TIP'); ?>"><?php echo JText::_('GROUP ACCESS'); ?></span></td>
                        <td><?php echo $this->lists['groups']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('RELEASE DATE'); ?>*</td>
                        <td><?php echo JHTML::_('calendar', $this->project->release_date, 'release_date', 'release_date'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CONTRACT FROM TO'); ?></td>
                        <td>
                            <?php echo JHTML::_('calendar', $this->project->contract_from, 'contract_from', 'contract_from'); ?>&nbsp;-&nbsp;
                            <?php echo JHTML::_('calendar', $this->project->contract_to, 'contract_to', 'contract_to'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('GEN LOC'); ?></td>
                        <td><textarea name="location_gen" class="inputbox" rows="4" cols="50"><?php echo $this->escape($this->project->location_gen); ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('SPEC LOC'); ?></td>
                        <td><textarea name="location_spec" class="inputbox" rows="4" cols="50"><?php echo $this->escape($this->project->location_spec); ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DESCRIPTION'); ?>*</td>
                        <td><?php echo $this->editor->display( 'description',  $this->project->description, '80%;', '250', '75', '20', array('pagebreak', 'readmore') ); ?></td>
                    </tr>
                </table>
             </td>
            <td width="30%" valign="top" style="border-left: solid 1px #ccc; padding: 0 0 0 8px;">
                <div style="background: #666; margin-bottom: 8px; border-bottom: solid 1px #999;padding: 3px 5px; font-weight: bold; color: #fff;">
                    <?php echo JText::_('PROJECT DETAILS'); ?>
                </div>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key"><?php echo JText::_('RELEASE #'); ?></td>
                        <td><input type="text" class="inputbox" name="release_id" value="<?php echo $this->project->release_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('JOB #'); ?></td>
                        <td><input type="text" class="inputbox" name="job_id" value="<?php echo $this->project->job_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('TASK #'); ?></td>
                        <td><input type="text" class="inputbox" name="task_id" value="<?php echo $this->project->task_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('WORKORDER #'); ?></td>
                        <td><input type="text" class="inputbox" name="workorder_id" value="<?php echo $this->project->workorder_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT TYPE'); ?></td>
                        <td><input type="text" class="inputbox" name="projecttype" value="<?php echo $this->project->projecttype; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CLIENT'); ?></td>
                        <td><input type="text" class="inputbox" name="client" value="<?php echo $this->project->client; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('STATUS'); ?>*</td>
                        <td><?php echo $this->lists['status']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT MANAGER'); ?>*</td>
                        <td><?php echo JHTML::_('list.users', 'manager', $this->project->manager, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT LEAD'); ?></td>
                        <td><?php echo JHTML::_('list.users', 'chief', $this->project->chief, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('TECHNICIAN'); ?></td>
                        <td><?php echo JHTML::_('list.users', 'technicians', $this->project->technicians, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DEPLOYMENT FROM'); ?></td>
                        <td><?php echo JHTML::_('calendar', $this->project->deployment_from, 'deployment_from', 'deployment_from'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DEPLOYMENT TO'); ?></td>
                        <td><?php echo JHTML::_('calendar', $this->project->deployment_to, 'deployment_to', 'deployment_to'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CREW ON SITE'); ?></td>
                        <td><?php echo $this->lists['onsite']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('APPROVED'); ?></td>
                        <td><?php echo $this->lists['approved']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PUBLISHED'); ?></td>
                        <td><?php echo $this->lists['published']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="id" value="<?php echo $this->project->id; ?>" />
	<input type="hidden" name="controller" value="projects" />
	<input type="hidden" name="view" value="project" />
	<input type="hidden" name="task" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	