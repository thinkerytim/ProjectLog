<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

jimport( 'joomla.application.component.view');

class ProjectlogViewProjectlog extends JView
{
	function display($tpl = null)
	{
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' );
		
		// Set toolbar logs for the page
		JToolBarHelper::title( JText::_( 'Projectlog' ), 'generic.png' );
        JToolbarHelper::preferences('com_projectlog', '200');
		
		parent::display($tpl);
	}
}
