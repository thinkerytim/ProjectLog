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
?>

<script language="javascript" type="text/javascript">

    function saveBindings() {
        var form = document.adminForm;

        var l = form.groupusers.length;
        var list = new Array();

        if(l==0) {
            alert("No Users To Bind");
            return false;
        }else{	
            for( x = 0; x < (l); x++ ) {
                list[x] = form.groupusers.options[x].value;
            }			
            form.gusers.value = list;
        }
    }

    function submitbutton(task)
	{
		var form = document.adminForm;

		if (task == 'cancel') {
			submitform( task );
		}  
		else {
			
			if (!document.formvalidator.isValid(form)) {
                //form.check.value='<?php echo JUtility::getToken(); ?>';//send token
                alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
                return false;
            }
            saveBindings();
			submitform( task );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="admintable" width="100%">
        <tr>
            <td class="key">Group Name:</td>
            <td><input class="inputbox required" type="text" name="name" size="50" maxlength="100" value="<?php echo $this->escape($this->group->name); ?>" /></td>
        </tr>
        <tr>
            <td class="key">Users:</td>
            <td>
                <table style="width:400px;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top"><?php echo JText::_('AVAILABLE USERS');?><br />
                            <select name="available" id="AvailableOptions" class="inputbox" size="7" multiple="multiple" style="width: 150px;">
                            <?php
                                foreach($this->users as $u) {
                                    echo '<option value="'.$u->id.'">'.$u->name.' ('.$u->username.')</option>';
                                }
                            ?>
                            </select>
                        </td>
                        <td valign="middle">
                            <input class="button" style="width:40px" type="button" value=">>" onClick="addSelectedToList('adminForm','available','groupusers')" title="<?php echo JText::_('ADD');?>" />
                            <br /><input class="button" style="width:40px" type="button" value="<<" onClick="delSelectedFromList('adminForm','groupusers')" title="<?php echo JText::_('REMOVE');?>" />
                        </td>
                        <td valign="top"><?php echo JText::_('GROUP USERS');?><br />
                            <select name="groupusers" id="SelectedOptions" class="inputbox" size="7" multiple="multiple" style="width: 150px;">
                            <?php
                                foreach($this->gusers as $g) {
                                    echo '<option value="'.$g->id.'">'.$g->name.' ('.$g->username.')</option>';
                                }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="id" value="<?php echo $this->group->id; ?>" />
	<input type="hidden" name="controller" value="groups" />
	<input type="hidden" name="view" value="group" />
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="gusers" value="0" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	