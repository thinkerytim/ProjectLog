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
		}  
		else {
			
			if (!document.formvalidator.isValid(form)) {
                //form.check.value='<?php echo JUtility::getToken(); ?>';//send token
                alert( '<?php echo JText::_('ENTER REQUIRED'); ?>' );
                return false;
            }
			submitform( task );
		}
	}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table width="100%" border="0" cellpadding="0" cellspacing="2">
        <tr>
        	<td width="65%" valign="top">
        	<table class="admintable" width="100%" border="0" cellspacing="0" cellpadding="4">
			<tr>
        		<td class="key"><?php echo JText::_('TITLE'); ?>*</td>
            	<td><input type="text" name="title" size="50" class="inputbox required" maxlength="100" value="<?php echo $this->row->title; ?>" /></td>
        	</tr>
        	</table>
  		</td>
		<td width="35%" valign="top">
			&nbsp;
		</td>
 	</tr>
 	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_projectlog" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="task" value="" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	