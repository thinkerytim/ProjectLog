<?php
/**
 * @version 1.5.1 2009-08-14
 * @package Joomla
 * @subpackage Intellectual Project
 * @copyright (C)  2008 the Thinkery
 * @license see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class projectlogViewcat extends JView
{
	function display()
	{
		global $mainframe;

		// Initialize some variables        
        $settings       = projectlogAdmin::config();		
		$model          = &$this->getModel();
		$catinfo	    = &$this->get('data');
		$rows		    = &$this->get('projects');
		$doc            = &JFactory::getDocument();
        $id      = JRequest::getVar('id', 0, '', 'string');

        JRequest::setVar('limitstart', 0);
		JRequest::setVar('limit', $settings->rss);

        $doc->setTitle(JText::_('PROPERTY RESULTS FOR') . ' ' . $catinfo->title);		
		$doc->link = JRoute::_('index.php?option=com_projectlog&view=cat&id='.$id);

		foreach ( $rows as $row )
		{			
			// strip html from feed item title
			$title = $row->street_address;
			$title = html_entity_decode( $title );
            $title = $title . ' - ' . $row->formattedprice;
            $category = $catinfo->title;

            $available_cats = projectlogHTML::getAvailableCats($row->id);
            $first_cat = $available_cats[0];
            $newitemid = '&Itemid=' . projectlogHTML::getItemid('cat', $first_cat);
            
			// & used instead of &amp; as this is converted by feed creator
			$link = JRoute::_('index.php?option=com_projectlog&view=project&id='.$row->id.$newitemid);

			// get short description
			$project_desc = ($row->short_description) ? $row->short_description : $row->description;
			$overview_char = ($settings->overview_char) ? $settings->overview_char : 250;
			$project_short_display = (strlen($project_desc) >= $overview_char ) ? substr( $project_desc, 0, $overview_char )  . '...' : $project_desc;
			$author			= "Intellectual Project - The Thinkery LLC";

			// load individual item creator class
			$item = new JFeedItem();
            $item->date         = $row->created;
			$item->title 		= $title;
			$item->link 		= $link;
			$item->description 	= $project_short_display;
            $item->author	    = $author;
			$item->category   	= $category;

			// loads item info into rss array
			$doc->addItem( $item );
		}
	}
}
