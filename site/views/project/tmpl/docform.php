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

if ( !projectlogHelperQuery::userAccess('dedit_access',$this->user->gid)){
	JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
	return;
}

$page_title = JText::_('ADD DOC');
?>
<script type="text/javascript">
    function checkForm()
    {
        if(document.adminForm.name.value == ''){
            alert('<?php echo JText::_('ENTER TITLE'); ?>');
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
            <legend><?php echo JText::_('ADD DOC'); ?></legend>
            <table class="adminform" width="100%">
                <tr>
                    <td><?php echo sprintf(JText::_('DOC OVERVIEW'), $this->settings->get('doc_types')); ?></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <input class="inputbox" type="text" id="name" name="name" size="50" maxlength="100" value="" /><br />
                            <input class="inputbox" type="file" id="doc" name="document" size="30" style="margin: 5px 0px;" />
                        </div>
                        <div>
                            <input type="submit" value="<?php echo JText::_('SAVE'); ?>" />
                            <input type="button" value="<?php echo JText::_('CANCEL'); ?>" onclick="history.go(-1)" />
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
    <input type="hidden" name="task" value="saveDoc" />
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>
<?php
if($this->settings->get('footer')) echo '<p class="copyright">'. projectlogAdmin::footer().'</p>';
echo JHTML::_('behavior.keepalive');
?>
