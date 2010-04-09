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
jimport('joomla.application.component.controller');
jimport('joomla.filter.filterinput');

class projectlogControllerEditcss extends projectlogController
{
	function __construct()
	{
		parent::__construct();		
		$this->registerTask( 'apply', 'save' );		
	}
 	
	function save()
	{
        $task=JRequest::getvar('task');
        $filecontent=JRequest::getvar('filecontent');
        $css_file=JRequest::getvar('css_file');
        $file = JPATH_COMPONENT_SITE.DS.'assets'.DS.'css'.DS.$css_file;
        //saving
        jimport('joomla.filesystem.file');
        $returnid = JFile::write($file, $filecontent);

        $returnid = true;

        if ($returnid) {
            switch ($task)
            {
                case 'apply' :
                    $link = 'index.php?option=com_projectlog&controller=editcss&view=editcss&task=edit_css&css_file='.$css_file;
                    break;

                default :
                    $link = 'index.php?option=com_projectlog';
                    break;
            }
            $msg	= JText::_( 'CSS SAVED');

            $cache = &JFactory::getCache('com_projectlog');
            $cache->clean();
        } else {
            $msg 	= '';
            $link = 'index.php?option=com_projectlog';
        }
        $this->setRedirect( $link, $msg );
	}
	 	
    function cancel()
    {
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );
        $this->setRedirect( 'index.php?option=com_projectlog' );
    }	 	
}
?>