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
    function submitbutton(task)
	{
		var form = document.adminForm;

		if (task == 'cancel') {
			submitform( task );
		}else{
			
			if (!document.formvalidator.isValid(form) || form.submittedby.selectedIndex == '') {
                alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
                return false;
            }

			submitform( task );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table width="100%">
        <tr>
            <td width="70%">
                <table class="admintable" width="100%">
                    <tr>
                        <td colspan="2">
                            <?php echo sprintf(JText::_('DOC OVERVIEW'),$this->allowed); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="key"><?php echo JText::_('TITLE'); ?>*</td>
                        <td width="80%" valign="top">
                            <input class="inputbox required" type="text" name="name" size="50" maxlength="100" value="<?php echo $this->escape($this->doc->name); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT'); ?>*</td>
                        <td><?php echo $this->lists['projects']; ?></td>
                    </tr>
                    <?php if($this->doc->path){ ?>
                    <tr>
                        <td class="key"><?php echo JText::_('FILE'); ?>*</td>
                        <td><?php echo $this->doc->path; ?></td>
                    </tr>
                    <?php }else{ ?>
                    <tr>
                        <td class="key"><?php echo JText::_('FILE'); ?>*</td>
                        <td><input class="inputbox" type="file" name="document" size="30" /></td>
                    </tr>
                    <?php } ?>
                </table>
             </td>
            <td width="30%" valign="top" style="border-left: solid 1px #ccc; padding: 0 0 0 8px;">
                <div style="background: #666; margin-bottom: 8px; border-bottom: solid 1px #999;padding: 3px 5px; font-weight: bold; color: #fff;">
                    <?php echo JText::_('DOC DETAILS'); ?>
                </div>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('DATE'); ?> :: <?php echo JText::_('DATE TIP'); ?>"><?php echo JText::_('DOC DATE'); ?></span>*</td>
                        <td><input class="inputbox required" type="text" name="date" size="50" maxlength="100" value="<?php echo $this->doc->date; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('SUBMITTED BY'); ?>*</td>
                        <td><?php echo JHTML::_('list.users', 'submittedby', $this->doc->submittedby, 1, NULL, 'name', 0 ); ?></td>
                    </tr>                    
                </table>
            </td>
        </tr>
    </table>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="id" value="<?php echo $this->doc->id; ?>" />
	<input type="hidden" name="controller" value="docs" />
	<input type="hidden" name="view" value="doc" />
	<input type="hidden" name="task" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	