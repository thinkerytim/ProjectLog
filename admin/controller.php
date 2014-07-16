<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ProjectlogController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{        
        $view   = $this->input->get('view', 'projects');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
        $views = array( 'category'=>'categories',
                        'project'=>'projects',
                        'log'=>'logs',
                        'doc'=>'docs',
                        'group'=>'groups');

        foreach( $views as $key => $value )
        {
            if ($view == $key && $layout == 'edit' && !$this->checkEditId('com_projectlog.edit.'.$key, $id)) 
            {
                // Somehow the person just went to the form - we don't allow that.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                $this->setMessage($this->getError(), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_projectlog&view='.$value, false));

                return false;
            }
        }
        
		parent::display($cachable);
        return $this;
	}
}


