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

class projectlogAdmin {

	function _getversion()
	{
		$xmlfile = JPATH_COMPONENT_ADMINISTRATOR.DS.'projectlog.xml';
		if (file_exists($xmlfile)) {
			$xmlDoc = new JSimpleXML();
			$xmlDoc->loadFile($xmlfile);
			return $xmlDoc->document->version[0]->_data;
		}
	}
	
	function footer( )
	{		
		echo '<div align="center"><a href="http://www.thethinkery.net" target="_blank">Project Log v.';
		echo projectlogAdmin::_getversion();		
		echo ' by The Thinkery LLC</a></div>';
	}
}

?>