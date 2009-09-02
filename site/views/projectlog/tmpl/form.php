<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

if ( $this->user->gid < $this->edit_access ){
	JError::raiseWarning( 403, JText::_('You are not authorized to view this area!') );
	return;
}

if(JRequest::getVar('edit')){
    $projectid = JRequest::getVar('edit');
    $this->project = ProjectlogModelProjectlog::getProject($projectid);
    $page_title = JText::_('Edit Project');
}else{
    $page_title = JText::_('Add Project');
}

$yesno = array();
$yesno[] = JHTML::_('select.option', 0, 'NO' );
$yesno[] = JHTML::_('select.option', 1, 'YES' );
$this->lists['onsite'] = JHTML::_('select.radiolist', $yesno, 'onsite', 'size="1" class="inputbox"', 'value', 'text', $this->project->onsite );
$this->lists['published'] = JHTML::_('select.radiolist', $yesno, 'published', 'size="1" class="inputbox"', 'value', 'text', $this->project->published );


$stats = array();
$stats[] = JHTML::_('select.option', '', JText::_( 'Select' ) );
$stats[] = JHTML::_('select.option', 'In Progress', JText::_( 'In Progress' ) );
$stats[] = JHTML::_('select.option', 'On Hold', JText::_( 'On Hold' ) );
$stats[] = JHTML::_('select.option', 'Complete', JText::_( 'Complete' ) );
$this->lists['status'] = JHTML::_('select.genericlist', $stats, 'status', 'size="1" class="inputbox"', 'value', 'text', $this->project->status );
?>

<script type="text/javascript">
    function checkForm()
    {
        if(document.adminForm.title.value == ''){
            alert('Please enter a project title');
            return false;
        }else if( document.adminForm.description.value == ''){
            alert('You must enter some text for the project description');
            return false;
        }
    }
</script>

<div class="main-article-title">
    <h2 class="contentheading"><?php echo $page_title; ?></h2>
</div>
<div class="main-article-block">
<form action="index.php" method="post" name="adminForm" onsubmit="return checkForm();">
<fieldset>
<legend><?php echo JText::_('Project Entry'); ?></legend>
<table class="adminform" width="100%">
<tr>
	<td align="right"><?php echo JText::_('Release Num'); ?>:</td>
    <td colspan="2" width="10%" valign="top">
        <input type="text" name="release_id" style="width: 70px;" value="<?php echo $this->project->release_id; ?>" class="inputbox" />&nbsp;&nbsp;
        <?php echo JText::_('Job Num'); ?>: <input type="text" name="job_id" style="width: 70px;" value="<?php echo $this->project->job_id; ?>" class="inputbox" />&nbsp;&nbsp;
        <?php echo JText::_('Task Num'); ?>: <input type="text" name="task_id" style="width: 70px;" value="<?php echo $this->project->task_id; ?>" class="inputbox" />&nbsp;&nbsp;
        <?php echo JText::_('Workorder Num'); ?>: <input type="text" name="workorder_id" style="width: 70px;" value="<?php echo $this->project->workorder_id; ?>" class="inputbox" />
    </td>
</tr>
<tr><td colspan="3"><hr></td></tr>
<tr>
	<td width="20%" valign="top" align="right">
        Title
    </td>
    <td width="50%" valign="top">
		<div>
			<input class="inputbox" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->project->title); ?>" />
		</div>	
	</td>
    <td rowspan="10" width="30%" valign="top" style="background: #f7f7f7; border-left: solid 1px #ccc; padding: 8px;">
        <div style="background: #666; border-bottom: solid 1px #999;padding: 3px 5px; font-weight: bold; color: #fff;">
            <?php echo JText::_('Project Details'); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Project type'); ?>:<br />
            <input type="text" name="surveytype" value="<?php echo $this->project->surveytype; ?>" />
        </div>
        <div class="form_details">
            <?php echo JText::_('Client'); ?>:<br />
            <input type="text" name="surveyor" value="<?php echo $this->project->surveyor; ?>" />
        </div>
        <div class="form_details">
            <?php echo JText::_('Status'); ?>:<br />
            <?php echo $this->lists['status']; ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Manager'); ?>:<br />
            <?php echo JHTML::_('list.users', 'manager', $this->project->manager, 1, NULL, 'name', 0 ); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Lead'); ?>:<br />
            <?php echo JHTML::_('list.users', 'chief', $this->project->chief, 1, NULL, 'name', 0 ); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Technician'); ?>:<br />
            <?php echo JHTML::_('list.users', 'technicians', $this->project->technicians, 1, NULL, 'name', 0 ); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Deployment From'); ?>:<br />
            <?php echo JHTML::_('calendar', $this->project->deployment_from, 'deployment_from', 'deployment_from'); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Deployment To'); ?>:<br />
            <?php echo JHTML::_('calendar', $this->project->deployment_to, 'deployment_to', 'deployment_to'); ?>
        </div>
        <div class="form_details">
            <?php echo JText::_('Crew On Site'); ?>:<br />
            <?php echo $this->lists['onsite']; ?>
        </div>
    </td>
</tr>
<tr>
	<td valign="top" align="right">
        <?php echo JText::_('Release Date'); ?>
    </td>
    <td valign="top">
		<div>
			<?php echo JHTML::_('calendar', $this->project->release_date, 'release_date', 'release_date'); ?>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" align="right">
        <?php echo JText::_('Contract From/To'); ?>
    </td>
    <td valign="top">
		<div>
			<?php echo JHTML::_('calendar', $this->project->contract_from, 'contract_from', 'contract_from'); ?>&nbsp;-&nbsp;
            <?php echo JHTML::_('calendar', $this->project->contract_to, 'contract_to', 'contract_to'); ?>
		</div>
	</td>
</tr>
<tr>
    <td valign="top" align="right">
        <?php echo JText::_('Description'); ?>
    </td>
    <td valign="top">
        <div style="margin: 5px 0px;">
            <textarea name="description" rows="8" cols="50"><?php echo $this->escape($this->project->description); ?></textarea>
        </div>
    </td>
</tr>
<tr>
    <td valign="top" align="right">
        <?php echo JText::_('General Location'); ?>
    </td>
    <td valign="top">
        <div style="margin: 5px 0px;">
            <textarea name="location_gen" rows="4" cols="50"><?php echo $this->escape($this->project->location_gen); ?></textarea>
        </div>
    </td>
</tr>
<tr>
    <td valign="top" align="right">
        <?php echo JText::_('Specific Location'); ?>
    </td>
    <td valign="top">
        <div style="margin: 5px 0px;">
            <textarea name="location_spec" rows="4" cols="50"><?php echo $this->escape($this->project->location_spec); ?></textarea>
        </div>
    </td>
</tr>
</table>
</fieldset>
<div>
    <input type="submit" value="save" />
    <input type="button" value="cancel" onclick="history.go(-1)" />
</div>
<input type="hidden" name="option" value="com_projectlog" />
<input type="hidden" name="view" value="projectlog" />
<input type="hidden" name="id" value="<?php echo $this->project->id; ?>" />
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="saveProject" />
</form>
</div>
<?php echo JHTML::_('behavior.keepalive'); ?>
