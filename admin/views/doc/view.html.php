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

class projectlogViewdoc extends JView {

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
        $this->allowed = $settings->get('doc_types');

		//get vars
		$cid 		= JRequest::getVar( 'cid' );
		// Get data from the model
		$model		= & $this->getModel();
		$doc  	= & $this->get( 'Data');
		
		$lists = array();
        $lists['projects'] = projectlogHTML::projectSelect('project_id', 'size="1" class="inputbox required" style="width: 150px;"', $doc->project_id);
		
        if ( $cid ) {
			JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT DOC' ) . ']</span>', 'projectlog' );
		}else{
            JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'ADD DOC' ) . ']</span>', 'projectlog' );
            $doc->submittedby = $user->id;
            $doc->date = JFactory::getDate()->toFormat('%Y-%m-%d %I:%M:%S');
        }
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		JHTML::_('behavior.modal', 'a.modal');

		//assign data to template
		$this->assignRef('doc'          , $doc);
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