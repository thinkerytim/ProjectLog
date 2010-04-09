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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
        <tr>
        	<td width="65%" valign="top">
            <p><?php echo '<b>'. JText::_('CATEGORIES') .'</b><br />'. JText::_('CATEGORY ADD DESC'); ?></p>
        	<p id="parah">--<?php echo JText::_('CLICK BELOW'); ?>--</p>
            <a href="javascript:deleteInput()"><img src="components/com_projectlog/assets/images/remove.png" alt="" /></a><a href="javascript:addInput()"><img src="components/com_projectlog/assets/images/add.png" alt="" /></a>
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


	