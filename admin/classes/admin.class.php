<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */
 
defined('_JEXEC') or die('Restricted access');

class projectlogAdmin {

	function _getversion()
	{
		$xmlfile = JPATH_COMPONENT_ADMINISTRATOR.'/projectlog.xml';
		if (file_exists($xmlfile)) {
			$xmlDoc = new JSimpleXML();
			$xmlDoc->loadFile($xmlfile);
			return $xmlDoc->document->version[0]->_data;
		}
	}
	
	function footer( )
	{		
		$version = projectlogAdmin::_getversion();
        $footer_display = '<p class="center small"><a href="http://www.thethinkery.net" target="_blank">Project Log v.'.$version.' by The Thinkery LLC</a></p>';

        echo $footer_display;
	}
}

?>