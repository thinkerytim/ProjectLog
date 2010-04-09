<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class projectlog_groups_mid extends JTable
{
	var $id                   = null;
    var $group_id   	      = null;
    var $user_id              = null;

    function __construct(&$db)
	{
		parent::__construct( '#__projectlog_groups_mid', 'id', $db );
	}	
}
?>