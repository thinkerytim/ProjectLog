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

class projectlogViewlog extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		// Load pane behavior
		JHTML::_('behavior.tooltip');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
        $user       = & JFactory::getUser();
		$settings	= & JComponentHelper::getParams( 'com_projectlog' );

		//get vars
		$cid 		= JRequest::getVar( 'cid' );
		// Get data from the model
		$model		= & $this->getModel();
		$log  	= & $this->get( 'Data');
		
		$lists = array();
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $log->published );
        $lists['projects'] = projectlogHTML::projectSelect('project_id', 'size="1" class="inputbox required" style="width: 150px;"', $log->project_id);
		
        if ( $cid ) {
			JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT LOG' ) . ']</span>', 'projectlog' );
		}else{
            JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'ADD LOG' ) . ']</span>', 'projectlog' );
            $log->loggedby = $user->id;
            $log->date = JFactory::getDate()->toFormat('%Y-%m-%d %I:%M:%S');
        }
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		JHTML::_('behavior.modal', 'a.modal');

		//assign data to template
		$this->assignRef('log'          , $log);
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('editor'      	, $editor);
		$this->assignRef('settings'     , $settings);

        $iconstyle = '<style type="text/css">
                        .invalid{background: #ffacac !important; border: solid 1px #ff0000;}
                        .icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}
                      </style>';
        $mainframe->addCustomHeadTag($iconstyle);

		parent::display($tpl);
	}
}
?>