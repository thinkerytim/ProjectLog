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
    $logid = JRequest::getVar('edit');
    $this->log = ProjectlogModelProject::getLog($logid);
    $page_title = "Edit Project Log";
}else{
    $page_title = "Add Project Log";
}
?>

<script type="text/javascript">
    function checkForm()
    {
        if(document.adminForm.title.value == ''){
            alert('Please enter a log title');
            return false;
        }else if( document.adminForm.description.value == ''){
            alert('You must enter some text for the log description');
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
<legend><?php echo JText::_('Log Entry'); ?></legend>
<table class="adminform" width="100%">
<tr>
	<td>
		<div>
			<input class="inputbox" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->log->title); ?>" />
		</div>
        <div style="margin: 5px 0px;">
            <textarea name="description" rows="8" cols="50"><?php echo $this->escape($this->log->description); ?></textarea>
        </div>
		<div>
			<input type="submit" value="save" />
			<input type="button" value="cancel" onclick="history.go(-1)" />
		</div>
	</td>
</tr>
</table>
</fieldset>
<input type="hidden" name="option" value="com_projectlog" />
<input type="hidden" name="view" value="project" />
<input type="hidden" name="id" value="<?php echo $this->log->id; ?>" />
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="project_id" value="<?php echo $this->project->id; ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="saveLog" />
</form>
</div>
<?php echo JHTML::_('behavior.keepalive'); ?>
