<?php
/**
 * @version 3.2 2013-11-26
 * @package Joomla
 * @subpackage Work Force
 * @copyright (C) 2013 the Thinkery
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

abstract class JHtmlPlAdministrator
{
	static function approve($value = 0, $i, $canChange = true)
	{        
        // Array of image, task, title, action
		$states	= array(
			0	=> array('thumbs-down',	'projects.approve',	'COM_PROJECTLOG_UNAPPROVED',	'COM_PROJECTLOG_APPROVE'),
			1	=> array('thumbs-up',		'projects.unapprove',	'COM_PROJECTLOG_APPROVED',		'COM_PROJECTLOG_UNAPPROVE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro ' . ($value == 1 ? 'active' : '') . '" rel="tooltip" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}
    
    static function status($value = 0, $i, $canChange = true)
	{        
        // Array of image, task, title, action
		$states	= array(
			0	=> array('star-empty',	'projects.feature',	'COM_WORKFORCE_UNFEATURED',	'COM_WORKFORCE_FEATURE'),
			1	=> array('star',		'projects.unfeature',	'COM_WORKFORCE_FEATURED',		'COM_WORKFORCE_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro ' . ($value == 1 ? 'active' : '') . '" rel="tooltip" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}
}