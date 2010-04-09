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

class projectlogViewproject extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		// Load pane behavior
		jimport('joomla.html.pane');
		JHTML::_('behavior.tooltip');
        JHTML::_('behavior.modal', 'a.modal');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$settings	= & JComponentHelper::getParams( 'com_projectlog' );

		//get vars
		$cid 		= JRequest::getVar( 'cid' );
		$model		= & $this->getModel();
		$project  	= & $this->get( 'Data');
		
		$lists = array();
        $lists['status'] = projectlogHTML::statusSelect('status', 'size="1" class="inputbox required"', $project->status);
        $lists['categories'] = projectlogHTML::catSelect('category', 'size="1" class="inputbox" style="width: 200px;"', $project->category);
        $lists['groups'] = projectlogHTML::groupSelect('group_access', 'size="1" class="inputbox" style="width: 200px;"', $project->group_access);
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $project->published );
        $lists['onsite'] = JHTML::_('select.booleanlist',  'onsite', 'class="inputbox"', $project->onsite );
        $lists['approved'] = JHTML::_('select.booleanlist',  'approved', 'class="inputbox"', $project->approved );		
		
        if ( $cid ) {
			JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT PROJECT' ) . ']</span>', 'projectlog' );
		}else{
            JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'ADD PROJECT' ) . ']</span>', 'projectlog' );
        }
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();		

		//assign data to template
		$this->assignRef('project'      , $project);
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('pane'      	, $pane);
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