<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_projectlog'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once (JPATH_COMPONENT_SITE.'/helpers/html.helper.php');
require_once (JPATH_COMPONENT.'/classes/admin.class.php');

$controller = JControllerLegacy::getInstance('projectlog');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
