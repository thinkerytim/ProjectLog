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

class projectlogViewcategory extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		// Load pane behavior
		jimport('joomla.html.pane');
		JHTML::_('behavior.tooltip');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$pane		= & JPane::getInstance('sliders');
		$user 		= & JFactory::getUser();
		$settings	= & JComponentHelper::getParams( 'com_projectlog' );
        $document->addScript('components/com_projectlog/assets/js/addInput.js');

		//get vars
		$cid 			= JRequest::getVar( 'cid' );
		// Get data from the model
		$model		= & $this->getModel();
		$row      	= & $this->get( 'Data');
		
		$lists = array();

        if ( $cid ) {
			JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'EDIT CATEGORY' ) . ']</span>', 'projectlog' );
            $tpl = 'edit';
        }else{
            JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'ADD CATEGORY' ) . ']</span>', 'projectlog' );
        }

		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		//assign data to template
		$this->assignRef('row'      	, $row);

        $iconstyle = '<style type="text/css">
                        .invalid{background: #ffacac !important; border: solid 1px #ff0000;}
                        .icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}
                      </style>';
        $mainframe->addCustomHeadTag($iconstyle);

		parent::display($tpl);
	}
}
?>