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
jimport('joomla.application.component.controller');

class projectlogControllerdocs extends projectlogController {

	function __construct()
	{		
		parent::__construct();
		
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$this->setRedirect( 'index.php?option=com_projectlog&view=docs' );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
        $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO DELETE' ) );
		}

		$model = $this->getModel('docs');
        $model->delete($cid);

		$total = count( $cid );
        $msg = $total . ' '.JText::_('ITEM DELETED');;

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_projectlog&view=docs', $msg );
	}

	function edit( )
	{
        JRequest::setVar( 'view', 'doc' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}
    
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$task		= JRequest::getVar('task');
		$post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('doc');
        $file = JRequest::getVar('document', null, 'files', 'array');
        if($file['name']){
            $error = $this->saveFile($file);
        }else{
            $error = JText::_('NO FILE');
        }

        if(!$error){
            $post['path'] = $src = $file['name'];

            if ($returnid = $model->store($post)) {

                switch ($task)
                {
                    case 'apply' :
                        $link = 'index.php?option=com_projectlog&controller=docs&view=doc&hidemainmenu=1&cid[]='.$returnid;
                        break;

                    default :
                        $link = 'index.php?option=com_projectlog&view=docs';
                        break;
                }
                $msg	= JText::_( 'ITEM SAVED');
                $type = 'message';

                $cache = &JFactory::getCache('com_projectlog');
                $cache->clean();

            } else {

                $msg 	= JText::_( 'ITEM NOT SAVED');
                $link = 'index.php?option=com_projectlog&view=docs';
                $type = 'notice';

            }

        }else{
            $link = 'index.php?option=com_projectlog&controller=docs&view=doc&hidemainmenu=1&cid[]='.$returnid;
            $msg = $error;
            $type = 'notice';
        }
		$this->setRedirect( $link, $msg, $type );
 	}

    function saveFile($file){
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
    }
}
?>
