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
jimport( 'joomla.application.component.controller' );

class projectlogController extends JController
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
        if( !JRequest::getVar('view')):
            JRequest::setVar( 'view', 'projects' );
        endif;

        parent::display();
	}
}

