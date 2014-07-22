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
        var desc = <?php echo $this->editor->getContent( 'description' ); ?>

		if (task == 'cancel') {
			submitform( task );
		}  
		else {
			
			if (!document.formvalidator.isValid(form)) {
                //form.check.value='<?php echo JUtility::getToken(); ?>';//send token
                alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
                return false;
            }else if(desc == ''){
                alert( '<?php echo JText::_('ENTER DESCRIPTION'); ?>' );
                return false;
            }
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
                            <input class="inputbox required" type="text" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->log->title); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('PROJECT'); ?>*</td>
                        <td><?php echo $this->lists['projects']; ?></td>
                    </tr>
                    
                    <tr>
                        <td class="key"><?php echo JText::_('DESCRIPTION'); ?>*</td>
                        <td><?php echo $this->editor->display( 'description',  $this->log->description, '80%;', '250', '75', '20', array('pagebreak', 'readmore') ); ?></td>
                    </tr>
                </table>
             </td>
            <td width="30%" valign="top" style="border-left: solid 1px #ccc; padding: 0 0 0 8px;">
                <div style="background: #666; margin-bottom: 8px; border-bottom: solid 1px #999;padding: 3px 5px; font-weight: bold; color: #fff;">
                    <?php echo JText::_('LOG DETAILS'); ?>
                </div>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('DATE'); ?> :: <?php echo JText::_('DATE TIP'); ?>"><?php echo JText::_('LOG DATE'); ?></span></td>
                        <td><input class="inputbox" type="text" name="date" size="50" maxlength="100" value="<?php echo $this->log->date; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('LOGGED BY'); ?></td>
                        <td><?php echo JHTML::_('list.users', 'loggedby', $this->log->loggedby, 1, NULL, 'name', 0 ); ?></td>
                    </tr>
                    <tr>
                        <td class="key"><span class="hasTip" title="<?php echo JText::_('DATE'); ?> :: <?php echo JText::_('MOD DATE TIP'); ?>"><?php echo JText::_('MOD DATE'); ?></span></td>
                        <td><input class="inputbox" type="text" name="modified" size="50" maxlength="100" value="<?php echo $this->log->modified; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="key"><?php echo JText::_('MODIFIED BY'); ?></td>
                        <td><?php echo JHTML::_('list.users', 'modified_by', $this->log->modified_by, 1, NULL, 'name', 0 ); ?></td>
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
	<input type="hidden" name="id" value="<?php echo $this->log->id; ?>" />
	<input type="hidden" name="controller" value="logs" />
	<input type="hidden" name="view" value="log" />
	<input type="hidden" name="task" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	