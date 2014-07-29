<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

abstract class ProjectlogHtml
{
	public static function getGravatar($email, $default = 'mm', $size = 35)
	{
		$gravatar = array();
        // Get gravatar Image 
        // https://fr.gravatar.com/site/implement/images/php/
        $gravatar['url']    = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $default . "&s=" . $size;
        $gravatar['image']  = JHtml::_('image', $gravatar['url'], JText::_('COM_PROJECTLOG_USER'));
        return $gravatar;
	}
}
