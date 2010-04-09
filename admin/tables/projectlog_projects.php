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

class projectlog_projects extends JTable
{
	var $id            = null;
    var $category      = null;
    var $group_access  = null;
    var $release_id    = null;
    var $job_id        = null;
    var $task_id       = null;
    var $workorder_id  = null;
    var $title         = null;
    var $description   = null;
    var $release_date  = null;
    var $contract_from = null;
    var $contract_to   = null;
    var $location_gen  = null;
    var $location_spec = null;
    var $manager       = null;
    var $chief         = null;
    var $technicians   = null;
    var $deployment_from = null;
    var $deployment_to = null;
    var $onsite        = null;
    var $projecttype    = null;
    var $client        = null;
    var $status        = null;
    var $approved      = null;
    var $created_by    = null;
    var $published     = 1;

    function __construct(&$db)
	{
		parent::__construct( '#__projectlog_projects', 'id', $db );
	}	
}
?>