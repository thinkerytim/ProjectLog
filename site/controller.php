<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Project Component Controller
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   3.3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/projectlog.php';
        require_once JPATH_COMPONENT_SITE.'/helpers/html.helper.php';
        
        $params = JComponentHelper::getParams('com_projectlog');
        
        if( $params->get('pl_offline') == 1 ){
            echo '
                <div class="pl-offline center">
                    '.JHtml::_('image', 'components/com_projectlog/assets/images/projectlog_logo.png', JText::_('COM_PROJECTLOG_OFFLINE')).'
                    <div>' . $params->get('pl_offline_message') . '</div>
                </div>';
        }else{
        
            $cachable = true;
            JFactory::getDocument()->addStyleSheet(JURI::root(true).'/components/com_projectlog/assets/css/projectlog.css');

            // Set the default view name and format from the Request.
            $vName = $this->input->get('view', 'categories');
            $this->input->set('view', $vName);

            $safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
                'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD');

            parent::display($cachable, $safeurlparams);

            return $this;
        }
	}
}
