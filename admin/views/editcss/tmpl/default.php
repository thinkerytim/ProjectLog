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
    <table class="adminform">
        <tr>
            <th><?php echo $this->filename; ?></th>
        </tr>
        <tr>
            <td>
                <textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $this->content; ?></textarea>
            </td>
        </tr>
    </table>
    <div class="clr"></div>

    <?php echo JHTML::_( 'form.token' ); ?>
    <input type="hidden" name="option" value="com_projectlog" />
    <input type="hidden" name="controller" value="editcss" />
    <input type="hidden" name="view" value="editcss" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="css_file" value="<?php echo $this->fname; ?>" />
</form>
<p class="copyright"><?php echo projectlogAdmin::footer( ); ?></p>
	