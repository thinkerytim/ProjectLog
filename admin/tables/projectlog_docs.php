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

class projectlog_docs extends JTable
{
	var $id                   = null;
    var $project_id           = null;
    var $name 			      = null;
    var $path       	      = null;
    var $date                 = null;
    var $submittedby          = null;

    function __construct(&$db)
	{
		parent::__construct( '#__projectlog_docs', 'id', $db );
	}	
}
?>