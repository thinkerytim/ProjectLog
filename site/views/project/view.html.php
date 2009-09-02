<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

jimport( 'joomla.application.component.view');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');

class ProjectlogViewProject extends JView
{
	function display($tpl = null)
	{
        global $option;
		JHTML::_('behavior.tooltip');
        $user = JFactory::getUser();
        $params     = &JComponentHelper::getParams( 'com_projectlog' );
        $this->basic_access = $params->get('basic_access');
        $this->log_access = $params->get('log_access');
        $this->doc_access = $params->get('doc_access');
        $this->edit_access = $params->get('edit_access');
	
		$document 	= & JFactory::getDocument();        
		$joomla_base = $this->baseurl;
		$document->addStyleSheet($joomla_base.'/components/com_projectlog/css/default.css');
	
		$model = &$this->getModel();
		$project = &$this->get('data');
		$logs = &$this->get('logs');
		$docs = &$this->get('docs');
		$document->setTitle( $project->title );	
		$doc_path = 'media/com_projectlog/docs/';
		
		$this->assignRef('user', $user);
        $this->assignRef('project', $project);
        $this->assignRef('logs', $logs);
		$this->assignRef('docs', $docs);
		$this->assignRef('doc_path', $doc_path);
		$this->assignRef('editor', $editor);
		parent::display($tpl);
    }
}
