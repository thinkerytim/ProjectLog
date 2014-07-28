<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Articles list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogControllerDocs extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config	An optional associative array of configuration settings.
	 *
	 * @return  ProjectlogControllerDocs
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
	 *
	 * @return  JModel
	 * @since   3.3.1
	 */
	public function getModel($name = 'Doc', $prefix = 'ProjectlogModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
    }
    
    /*public function saveFile($file)
    {
        //Import filesystem libraries. Perhaps not necessary, but does not hurt
        jimport('joomla.filesystem.file');
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
        $allowed = explode(',',trim($settings->get('doc_types')));

        //Clean up filename to get rid of strange characters like spaces etc
        $filename = JFile::makeSafe($file['name']);

        //Set up the source and destination of the file
        $src = $file['tmp_name'];
        $dest = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS.$filename;
        $ext = strtolower(JFile::getExt($filename) );

        if(file_exists($dest)){
            return JText::_('FILE EXISTS').' - '.$filename;
        }

        //Verify this is an acceptable doc file
        if (in_array($ext,$allowed)) {
           if ( JFile::upload($src, $dest) ) {
              //continue
           } else {
              return JText::_('FILE NOT UPLOADED');
           }
        } else {
           return sprintf(JText::_('WRONG FILE TYPE'),$ext);
        }
    }*/
}
?>
