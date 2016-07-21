<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Component Controller
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 */
class ProjectlogController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since   3.3.1
	 */
	protected $default_view = 'projects';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array           An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   3.3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/projectlog.php';

		$view   = $this->input->get('view', 'projects');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
        
        $views = array('project','log','doc');

		// Check for edit form.
        foreach($views as $v){
            if ($view == $v && $layout == 'edit' && !$this->checkEditId('com_projectlog.edit.'.$v, $id)) {

                // Somehow the person just went to the form - we don't allow that.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                $this->setMessage($this->getError(), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_projectlog&view='.$v.'s', false));

                return false;
            }
        }
        
        //include PL css
        JFactory::getDocument()->addStyleSheet(JURI::root(true).'/components/com_projectlog/assets/css/projectlog.css');

		parent::display();

		return $this;
	}
}
