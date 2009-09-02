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

jimport( 'joomla.application.component.controller' );
class ProjectlogController extends JController
{
	var $debug = true;
	function __construct()
	{
		parent::__construct();
	}
	
	function display()
	{
		$user 		= & JFactory::getUser();
		$document	= & JFactory::getDocument();
	
		$view	= 'projectlog';
		parent::display();
	}
}
