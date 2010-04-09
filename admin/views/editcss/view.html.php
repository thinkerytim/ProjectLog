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
jimport( 'joomla.application.component.view');

class projectlogViewEditcss extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		// Initialize some variables
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$option		= JRequest::getCmd('option');
		$fname	    = JRequest::getVar('css_file');
		$filename   = JPATH_COMPONENT_SITE.DS.'assets'.DS.'css'.DS.$fname;
		
		jimport('joomla.filesystem.file');

		if (JFile::getExt($filename) !== 'css') {
			$msg = JText::_('CSS WRONG TYPE');
			$mainframe->redirect('index.php?option='.$option, $msg, 'error');
		}

		$content = JFile::read($filename);

		if ($content !== false)
		{
			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
			$this->editcssSource($fname, $filename, $content);
		}
		else
		{
			$msg = JText::sprintf('COULD NOT OPEN', $client->path.$filename);
			$mainframe->redirect('index.php?option='.$option, $msg);
		}
	}	
	
	function editcssSource($fname, $filename, & $content)
	{
		global $mainframe;
        JRequest::setVar( 'hidemainmenu', 1 );

		JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT CSS' ) . ']</span>', 'projectlog' );

		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		$this->assignRef('filename'      	, $filename);
		$this->assignRef('fname'      	    , $fname);
		$this->assignRef('content'      	, $content);

        $iconstyle = '<style type="text/css">.icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}</style>';
        $mainframe->addCustomHeadTag($iconstyle);
		
		parent::display();
	}
}
?>