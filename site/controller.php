<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'admin.class.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html.helper.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'query.php');

class projectlogController extends JController
{
	function display()
	{
		$params = & JComponentHelper::getParams( 'com_projectlog' );
		$offline = $params->get('offline');
		if( $offline == 1 ){
			echo '<div align="center">
					<a href="http://www.thethinkery.net" target="_blank">
						<img src="administrator/components/com_projectlog/assets/images/projectlog1.jpg" border="0" alt="Project Log" />
					</a><br />
					<strong>' . $params->get('offmessage') . '</strong>
				  </div>';
		}else{
            $user = JFactory::getUser();
            $basic_access  = projectlogHelperQuery::userAccess('basic_access',$user->gid);
            $log_access    = projectlogHelperQuery::userAccess('log_access',$user->gid);
            $doc_access    = projectlogHelperQuery::userAccess('doc_access',$user->gid);
            $pedit_access  = projectlogHelperQuery::userAccess('pedit_access',$user->gid);
            $ledit_access  = projectlogHelperQuery::userAccess('ledit_access',$user->gid);
            $dedit_access  = projectlogHelperQuery::userAccess('dedit_access',$user->gid);
            $plog_admin    = ($user->get('gid') >= 24) ? true : false;
            define('BASIC_ACCESS', $basic_access);
            define('LOG_ACCESS', $log_access);
            define('DOC_ACCESS', $doc_access);
            define('PEDIT_ACCESS', $pedit_access);
            define('LEDIT_ACCESS', $ledit_access);
            define('DEDIT_ACCESS', $dedit_access);
            define('PLOG_ADMIN', $plog_admin);

            if( !BASIC_ACCESS ){
                echo '<div align="center">
                        <a href="http://www.thethinkery.net" target="_blank">
                            <img src="administrator/components/com_projectlog/assets/images/projectlog1.jpg" border="0" alt="Project Log" />
                        </a><br />
                        <strong>' . JText::_('PLOG NOT AUTHORIZED') . '</strong>
                      </div>';
            }else{
                parent::display();
            }

            $task = JRequest::getVar('task');
            switch( $task )
            {
                case 'saveProject':
                    if( PEDIT_ACCESS ):
                        jimport( 'joomla.mail.helper' );
                        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
                        $post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
                        JRequest::checkToken() or die( 'Invalid Token!' );
                        $model = $this->getModel('cat');
                        if($model->saveProject($post)){
                            $msg = JText::_('PROJECT SAVED');
                            if($settings->get('approval') && !$post['id']) $msg .= ' -- '.JText::_('APPROVAL REQUIRED');
                            $type = 'message';
                        }else{
                            $msg = JText::_('PROJECT NOT SAVED'.' - '.$model->getError());
                            $type = 'notice';
                        }
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&id='.$post['category'].'&Itemid='.$post['Itemid'], false);
                        $this->setRedirect($link, $msg, $type);
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case 'deleteProject':
                    if( PEDIT_ACCESS ):
                        $id = JRequest::getVar('id');
                        $cat_id = JRequest::getVar('category_id');
                        $item_id = JRequest::getVar('Itemid');
                        $model = $this->getModel('cat');
                        if($model->deleteProject($id)){
                            $msg = JText::_('PROJECT DELETED');
                            $type = 'message';
                        }else{
                            $msg = JText::_('PROJECT NOT DELETED'.' - '.$model->getError());
                            $type = 'notice';
                        }
                        $link = JRoute::_('index.php?option=com_projectlog&view=cat&id='.$cat_id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, $msg, $type);
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case 'saveLog':
                    if( LEDIT_ACCESS ):
                        jimport( 'joomla.mail.helper' );
                        $post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
                        JRequest::checkToken() or die( 'Invalid Token!' );
                        $model = $this->getModel('project');
                        if($model->saveLog($post)){
                            $msg = JText::_('LOG SAVED');
                            $type = 'message';
                        }else{
                            $msg = JText::_('LOG NOT SAVED'.' - '.$model->getError());
                            $type = 'notice';
                        }
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                        $this->setRedirect($link, $msg, $type);
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case 'deleteLog':
                    if( LEDIT_ACCESS ):
                        $id = JRequest::getVar('id');
                        $project_id = JRequest::getVar('project_id');
                        $item_id = JRequest::getVar('Itemid');
                        $view = JRequest::getVar('view');
                        $model = $this->getModel('project');
                        if($model->deleteLog($id)){
                            $msg = JText::_('LOG DELETED');
                            $type = 'message';
                        }else{
                            $msg = JText::_('LOG NOT DELETED'.' - '.$model->getError());
                            $type = 'notice';
                        }
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&id='.$project_id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, $msg, $type);
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case 'saveDoc':
                    if( DEDIT_ACCESS ):
                        global $mainframe;
                        jimport( 'joomla.mail.helper' );
                        $post = JRequest::get('post');
                        JRequest::checkToken() or die( 'Invalid Token!' );
                        $model = $this->getModel('project');
                        $file = JRequest::getVar('document', null, 'files', 'array');
                        if($file['name']){
                            $error = $model->saveFile($file);                        

                            if(!$error){
                                $post['path'] = $file['name'];
                            }else{
                                $link = JRoute::_('index.php?option=com_projectlog&view=project&layout=docform&id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                                $msg = JText::_('DOC NOT SAVED').' - '. $error;
                                $type = 'notice';
                                $mainframe->redirect($link, $msg, $type);
                            }
                            if($model->saveDoc($post)){
                                $msg = JText::_('DOC SAVED');
                                $type = 'message';
                            }else{
                                $msg = JText::_('DOC NOT SAVED'.' - '.$model->getError());
                                $type = 'notice';
                            }
                            $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                            $this->setRedirect($link, $msg, $type);
                        }else{
                            $link = JRoute::_('index.php?option=com_projectlog&view=project&layout=docform&id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                            $msg = JText::_('NO FILE');
                            $type = 'notice';
                            $this->setRedirect($link, $msg, $type);
                        }
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case 'deleteDoc':
                    if( DEDIT_ACCESS ):
                        $id = JRequest::getVar('id');
                        $project_id = JRequest::getVar('project_id');
                        $item_id = JRequest::getVar('Itemid');
                        $view = JRequest::getVar('view');
                        $model = $this->getModel('project');
                        if($model->deleteDoc($id)){
                            $msg = JText::_('DOC DELETED');
                            $type = 'message';
                        }else{
                            $msg = Jtext::_('DOC NOT DELETED').' - '.$model->getError();
                            $type = 'notice';
                        }
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&id='.$project_id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, $msg, $type);
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case "projectOnsite":
                    if( PEDIT_ACCESS ):
                        $cid = JRequest::getVar('project_edit');
                        $id = JRequest::getVar('id');
                        $item_id = JRequest::getVar('Itemid');
                        $view = JRequest::getVar('view');
                        $model = $this->getModel('cat');
                        $model->projectSitestatus($cid, 1);
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&id='.$id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, '');
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case "projectOffsite":
                    if( PEDIT_ACCESS ):
                        $cid = JRequest::getVar('project_edit');
                        $id = JRequest::getVar('id');
                        $item_id = JRequest::getVar('Itemid');
                        $view = JRequest::getVar('view');
                        $model = $this->getModel('cat');
                        $model->projectSitestatus($cid, 0);
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&id='.$id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, '');
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;

                case "changeStatus":
                    if( PEDIT_ACCESS ):
                        $cid = JRequest::getVar('project_edit');
                        $id = JRequest::getVar('id');
                        $item_id = JRequest::getVar('Itemid');
                        $view = JRequest::getVar('view');
                        $model = $this->getModel('cat');
                        $model->changeStatus($cid);
                        $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&id='.$id.'&Itemid='.$item_id, false);
                        $this->setRedirect($link, '');
                    else:
                        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED') );
                        return;
                    endif;
                break;
            }
		}		
	}	
}

?>