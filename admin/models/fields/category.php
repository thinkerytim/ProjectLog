<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementcategory extends JElement
{
	var	$_name = 'category';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT c.id, c.title'
		. ' FROM #__projectlog_categories AS c'
		. ' ORDER BY c.title ASC'
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList();

		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('SELECT CATEGORY').' -', 'id', 'title'));

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'title', $value, $control_name.$name );
	}
}
