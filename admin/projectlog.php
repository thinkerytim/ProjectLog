<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_property')) {
	return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once (JPATH_COMPONENT.'/controller.php');
require_once (JPATH_COMPONENT.'/classes/admin.class.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/html.helper.php');

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Projectlog');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
