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
 * Logs list controller class.
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogControllerLogs extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config	An optional associative array of configuration settings.
	 *
	 * @return  ProjectlogControllerLogs
	 * @see     JController
	 * @since   3.3.1
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the PHP class name.
     * @param   array   $string Array of config options
	 *
	 * @return  JModel
	 * @since   3.3.1
	 */
	public function getModel($name = 'Log', $prefix = 'ProjectlogModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}

