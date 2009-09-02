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

define( 'COM_PROJECTLOG_DIR', 'media'.DS.'com_projectlog'.DS.'docs'.DS );
define( 'COM_PROJECTLOG_BASE', JPATH_ROOT.DS.COM_PROJECTLOG_DIR );
define( 'COM_PROJECTLOG_BASEURL', JURI::root().str_replace( DS, '/', COM_PROJECTLOG_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new ProjectlogController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

