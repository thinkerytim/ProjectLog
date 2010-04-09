<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
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

