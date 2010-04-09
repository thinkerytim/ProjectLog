<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
JHTMLBehavior::formvalidation();
$editor 	= & JFactory::getEditor();

if ( !projectlogHelperQuery::userAccess('pedit_access',$this->user->gid)){
	JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
	return;
}

if(JRequest::getVar('edit')){
    $pid = JRequest::getVar('edit');
    $this->project = projectlogModelCat::getProject($pid);
    $page_title = JText::_('EDIT PROJECT');
}else{
    $project = new stdClass();
    $project->id			= 0;
    $project->category      = null;
    $project->group_access  = null;
    $project->release_id    = null;
    $project->job_id        = null;
    $project->task_id       = null;
    $project->workorder_id  = null;
    $project->title         = null;
    $project->description   = null;
    $project->release_date  = null;
    $project->contract_from = null;
    $project->contract_to   = null;
    $project->location_gen  = null;
    $project->location_spec = null;
    $project->manager       = null;
    $project->chief         = null;
    $project->technicians   = null;
    $project->deployment_from = null;
    $project->deployment_to = null;
    $project->onsite        = null;
    $project->projecttype    = null;
    $project->client        = null;
    $project->status        = null;
    $project->approved      = null;
    $project->published     = null;

    $this->project			           = $project;
    $page_title = JText::_('ADD PROJECT');
}

$lists['groups'] = projectlogHTML::groupSelect('group_access', 'size="1" class="inputbox" style="width: 200px;"', $this->project->group_access);
$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $this->project->published );
$lists['onsite'] = JHTML::_('select.booleanlist',  'onsite', 'class="inputbox"', $this->project->onsite );
$lists['approved'] = JHTML::_('select.booleanlist',  'approved', 'class="inputbox"', $this->project->approved );
$lists['status'] = projectlogHTML::statusSelect('status', 'size="1" class="inputbox required"', $this->project->status);
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
        }else if(form.release_date.value == ''){
            alert( '<?php echo JText::_('ENTER RELEASE DATE'); ?>' );
            return false;
        }else if(form.manager.selectedIndex == ''){
            alert( '<?php echo JText::_('ENTER MANAGER'); ?>' );
            return false;
        }
    }
</script>

<div class="main-article-title">
    <h2 class="contentheading"><?php echo $page_title; ?></h2>
</div>
<div class="main-article-block">
<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return checkForm();">
	<table width="100%">
        <tr>
            <td width="70%" valign="top">
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key"><?php echo JText::_('TITLE'); ?>*<br />
                            <input class="inputbox required" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->project->title); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('GROUP ACCESS'); ?>  :: <?PHP echo JText::_('GROUP ACCESS TIP'); ?>"><?php echo JText::_('GROUP ACCESS'); ?></span><br />
                        <?php echo $lists['groups']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('RELEASE DATE'); ?>*<br />
                        <?php echo JHTML::_('calendar', $this->project->release_date, 'release_date', 'release_date'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CONTRACT FROM TO'); ?><br />
                            <?php echo JHTML::_('calendar', $this->project->contract_from, 'contract_from', 'contract_from'); ?>&nbsp;-&nbsp;
                            <?php echo JHTML::_('calendar', $this->project->contract_to, 'contract_to', 'contract_to'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('GEN LOC'); ?><br />
                        <textarea name="location_gen" class="inputbox" rows="4" cols="30"><?php echo $this->escape($this->project->location_gen); ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('SPEC LOC'); ?><br />
                        <textarea name="location_spec" class="inputbox" rows="4" cols="30"><?php echo $this->escape($this->project->location_spec); ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DESCRIPTION'); ?>*<br />
                        <?php if($this->settings->get('plogeditor')):
                            echo $editor->display( 'description',  $this->project->description, '90%;', '250', '75', '20', array('pagebreak', 'readmore') );
                        else: ?>
                            <textarea name="description" rows="8" cols="50"><?php echo $this->project->description; ?></textarea>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" value="<?php echo JText::_('SAVE'); ?>" />
                            <input type="button" value="<?php echo JText::_('CANCEL'); ?>" onclick="history.go(-1)" />
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30%" valign="top" style="border-left: solid 1px #ccc; padding: 0 0 0 8px;">
                <div style="background: #666; margin-bottom: 8px; border-bottom: solid 1px #999;padding: 3px 5px; font-weight: bold; color: #fff;">
                    <?php echo JText::_('PROJECT DETAILS'); ?>
                </div>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key"><?php echo JText::_('RELEASE NUM'); ?><br />
                        <input type="text" class="inputbox" name="release_id" value="<?php echo $this->project->release_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('JOB NUM'); ?><br />
                        <input type="text" class="inputbox" name="job_id" value="<?php echo $this->project->job_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('TASK NUM'); ?><br />
                        <input type="text" class="inputbox" name="task_id" value="<?php echo $this->project->task_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('WORKORDER NUM'); ?><br />
                        <input type="text" class="inputbox" name="workorder_id" value="<?php echo $this->project->workorder_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT TYPE'); ?><br />
                        <input type="text" class="inputbox" name="projecttype" value="<?php echo $this->project->projecttype; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CLIENT'); ?><br />
                        <input type="text" class="inputbox" name="client" value="<?php echo $this->project->client; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('STATUS'); ?>*<br />
                        <?php echo $lists['status']; ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT MANAGER'); ?>*<br />
                        <?php echo JHTML::_('list.users', 'manager', $this->project->manager, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT LEAD'); ?><br />
                        <?php echo JHTML::_('list.users', 'chief', $this->project->chief, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('TECHNICIAN'); ?><br />
                        <?php echo JHTML::_('list.users', 'technicians', $this->project->technicians, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DEPLOYMENT FROM'); ?><br />
                        <?php echo JHTML::_('calendar', $this->project->deployment_from, 'deployment_from', 'deployment_from'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('DEPLOYMENT TO'); ?><br />
                        <?php echo JHTML::_('calendar', $this->project->deployment_to, 'deployment_to', 'deployment_to'); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('CREW ON SITE'); ?><br />
                        <?php echo $lists['onsite']; ?></td>
                    </tr>                   
                </table>
            </td>
        </tr>
    </table>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="id" value="<?php echo $this->project->id; ?>" />
	<input type="hidden" name="view" value="cat" />
	<input type="hidden" name="task" value="saveProject" />
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
    <input type="hidden" name="category" value="<?php echo JRequest::getVar('cid'); ?>" />
</form>
</div>
<?php
if($this->settings->get('footer')) echo '<p class="copyright">'. projectlogAdmin::footer().'</p>';
echo JHTML::_('behavior.keepalive');
?>
	