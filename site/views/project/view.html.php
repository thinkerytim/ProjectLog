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
defined('_JEXEC') or die('No access');
jimport( 'joomla.application.component.view');

class ProjectlogViewProject extends JView
{
	function display($tpl = null)
	{
        global $option;
		JHTML::_('behavior.tooltip');
        $user = JFactory::getUser();
	
		$settings      = &JComponentHelper::getParams( 'com_projectlog' );
        $document 	= & JFactory::getDocument();
		$this->baseurl = JURI::root(true);
		$document->addStyleSheet($this->baseurl.'/components/com_projectlog/assets/css/projectlog.css');
	
		$model = &$this->getModel();
		$project = &$this->get('data');
		$logs = &$this->get('logs');
		$docs = &$this->get('docs');
		$document->setTitle( $project->title );	
		$doc_path = $this->baseurl.'/media/com_projectlog/docs/';
		
		$this->assignRef('user', $user);
        $this->assignRef('project', $project);
        $this->assignRef('logs', $logs);
		$this->assignRef('docs', $docs);
		$this->assignRef('doc_path', $doc_path);
		$this->assignRef('settings', $settings);
		parent::display($tpl);
    }
}
