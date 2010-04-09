<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');
JHTMLBehavior::formvalidation();
$editor 	= & JFactory::getEditor();

if ( !projectlogHelperQuery::userAccess('ledit_access',$this->user->gid)){
	JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
	return;
}

if(JRequest::getVar('edit')){
    $logid = JRequest::getVar('edit');
    $this->log = ProjectlogModelProject::getLog($logid);
    $page_title = JText::_('EDIT LOG');
}else{
    $log = new stdClass();
    $log->id				   = 0;
    $log->project_id           = null;
    $log->title 			   = null;
    $log->description	       = null;
    $log->date                 = null;
    $log->loggedby             = null;
    $log->modified             = null;
    $log->modified_by          = null;
    $log->ordering             = null;
    $log->published            = 1;

    $this->log  			   = $log;
    $page_title = JText::_('ADD LOG');
}
?>

<script type="text/javascript">
    function checkForm()
    {
        var form = document.adminForm;
        <?php if($this->settings->get('plogeditor')){ ?>
            var desc = <?php echo $editor->getContent( 'description' ); ?>;
            if( desc == ''){
                alert('<?php echo JText::_('ENTER DESC'); ?>');
                return false;
            }
        <?php
        $editor->save('description');
        }else{ ?>
            if( document.adminForm.description.value == ''){
                alert('<?php echo JText::_('ENTER DESC'); ?>');
                return false;
            }
        <?php } ?>

        if (!document.formvalidator.isValid(form)) {
            //form.check.value='<?php echo JUtility::getToken(); ?>';//send token
            alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
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
<legend><?php echo JText::_('LOG ENTRY'); ?></legend>
<table class="adminform" width="100%">
<tr>
	<td>
		<div>
			<input class="inputbox required" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->log->title); ?>" />
		</div>
        <div style="margin: 5px 0px;">
        <?php if($this->settings->get('plogeditor')):
            echo $editor->display( 'description',  $this->log->description, '80%;', '250', '75', '20', array('pagebreak', 'readmore') );
        else: ?>
            <textarea class="inputbox required" name="description" rows="8" cols="50"><?php echo $this->log->description; ?></textarea>
        <?php endif; ?>
        </div>
		<div style="clear:both;">
			<input type="submit" value="<?php echo JText::_('SAVE'); ?>" />
			<input type="button" value="<?php echo JText::_('CANCEL'); ?>" onclick="window.history.go(-1)" />
		</div>
	</td>
</tr>
</table>
</fieldset>
<input type="hidden" name="option" value="com_projectlog" />
<input type="hidden" name="view" value="project" />
<input type="hidden" name="id" value="<?php echo $this->log->id; ?>" />
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="project_id" value="<?php echo JRequest::getVar('id'); ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
<input type="hidden" name="published" value="1" />

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="saveLog" />
</form>
</div>
<?php
if($this->settings->get('footer')) echo '<p class="copyright">'. projectlogAdmin::footer().'</p>';
echo JHTML::_('behavior.keepalive');
?>
