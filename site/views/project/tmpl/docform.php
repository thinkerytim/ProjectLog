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

$page_title = "Add Project Document";
?>
<script type="text/javascript">
    function checkForm()
    {
        if(document.adminForm.name.value == ''){
            alert('Please enter a document name');
            return false;
        }
    }
</script>


<div class="main-article-title">
    <h2 class="contentheading"><?php echo $page_title; ?></h2>
</div>
<div class="main-article-block">
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" onsubmit="return checkForm();">
<fieldset>
<legend><?php echo JText::_('Add Document'); ?></legend>
<table class="adminform" width="100%">
<tr>
	<td>
		Please upload any related project documents. Allowed file types are as follows:<br />
        <span class="red">.doc, .odf, .pdf, .zip, .jpg, .jpeg, .gif, .dwg, .dgn, .tif, .xls, .ppt, .bmp.</span>
	</td>
</tr>
<tr>
	<td>
		<div>
			<input class="inputbox" type="text" id="name" name="name" size="50" maxlength="100" value="" /><br />
            <input class="inputbox" type="file" id="doc" name="document" size="30" style="margin: 5px 0px;" />
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
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="project_id" value="<?php echo $this->project->id; ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="saveDoc" />
</form>
</div>
<?php echo JHTML::_('behavior.keepalive'); ?>
