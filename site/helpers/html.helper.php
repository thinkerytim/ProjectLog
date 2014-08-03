<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Projectlog Html Helper
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
abstract class ProjectlogHtml
{
    /**
	 * Method to get a gravatar image from user email
	 *
	 * @param   string  $email  Email address
	 * @param   string  $mm     
     * @param   string  $size   Image size
	 *
	 * @return  array   Array of image url and actual image html
	 *
	 * @since  3.3.1
	 */
    public static function getGravatar($email, $default = 'mm', $size = 35)
	{
		$gravatar = array();
        // Get gravatar Image 
        // https://fr.gravatar.com/site/implement/images/php/
        $gravatar['url']    = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $default . "&s=" . $size;
        $gravatar['image']  = JHtml::_('image', $gravatar['url'], JText::_('COM_PROJECTLOG_USER'));
        return $gravatar;
	}
    
    /**
	 * Method to build the Thinkery footer
	 *
	 * @return  html   footer html
	 *
	 * @since  3.3.1
	 */    
    public static function buildThinkeryFooter()
    {
        require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/projectlog.php';
        $plversion = projectlogHelper::_getversion();
        return '<p class="small pagination-centered pl-footer">'.sprintf(JText::_('COM_PROJECTLOG_FOOTER'), Jhtml::_('link', 'http://thethinkery.net', 'The Thinkery LLC', array('target' => '_blank')), $plversion).'</p>';
    }
}
