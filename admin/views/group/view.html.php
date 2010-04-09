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

class projectlogViewgroup extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		// Load pane behavior
		JHTML::_('behavior.tooltip');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$settings	= & JComponentHelper::getParams( 'com_projectlog' );

		//get vars
		$cid 		= JRequest::getVar( 'cid' );
		// Get data from the model
		$model		= & $this->getModel();
		$group  	= & $this->get( 'Data');
        $users      = & $this->get( 'Users' );
        $gusers     = & $this->get( 'Gusers' );
		
		$lists = array();
		
        if ( $cid ) {
			JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT GROUP' ) . ']</span>', 'projectlog' );
		}else{
            JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'ADD GROUP' ) . ']</span>', 'projectlog' );
        }
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		JHTML::_('behavior.modal', 'a.modal');

		//assign data to template
		$this->assignRef('group'          , $group);
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('editor'      	, $editor);
		$this->assignRef('settings'     , $settings);
        $this->assignRef('users'     , $users);
        $this->assignRef('gusers'     , $gusers);


        $iconstyle = '<style type="text/css">
                        .invalid{background: #ffacac !important; border: solid 1px #ff0000;}
                        .icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}
                      </style>';
        $mainframe->addCustomHeadTag($iconstyle);

		parent::display($tpl);
	}
}
?>