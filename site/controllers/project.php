<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 */
class ProjectlogControllerProject extends JControllerForm
{
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
    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
    
    /**
	 * Method to handle project contact form
	 *
	 * @return  boolean true if successful, false if not
	 * @since   3.3.1
	 */
	public function submitContact()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app    = JFactory::getApplication();
		$model  = $this->getModel('project');
		$params = JComponentHelper::getParams('com_projectlog');
		$stub   = $this->input->getString('id');
		$id     = (int) $stub;

		// Get the data from POST
		$data  = $this->input->post->get('jform', array(), 'array');

		$project = $model->getItem($id);

		$params->merge($project->params);

		// Check for a valid session cookie
		if ($params->get('validate_session', 0))
		{
			if (JFactory::getSession()->getState() != 'active')
			{
				JError::raiseWarning(403, JText::_('COM_PROJECTLOG_SESSION_INVALID'));

				// Save the data in the session.
				$app->setUserState('com_projectlog.contact.data', $data);

				// Redirect back to the project.
				$this->setRedirect(JRoute::_('index.php?option=com_projectlog&view=project&id=' . $stub, false));

				return false;
			}
		}

		// Project plugins
		JPluginHelper::importPlugin('projectlog');
		$dispatcher	= JEventDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_projectlog.contact.data', $data);

			// Redirect back to the project.
			$this->setRedirect(JRoute::_('index.php?option=com_projectlog&view=project&id=' . $stub, false));

			return false;
		}

		// Validation succeeded, continue with custom handlers
		$results	= $dispatcher->trigger('onValidateProjectContact', array(&$project, &$data));

		foreach ($results as $result)
		{
			if ($result instanceof Exception)
			{
				return false;
			}
		}

		// Passed Validation: Process the projectlog plugins to integrate with other applications
		$dispatcher->trigger('onSubmitProjectContact', array(&$project, &$data));

		// Send the email
		$sent = false;

		if (!$params->get('custom_reply'))
		{
			$sent = $this->_sendEmail($data, $project, $params->get('show_email_copy'));
		}

		// Set the success message if it was a success
		if (!($sent instanceof Exception))
		{
			$msg = JText::_('COM_PROJECTLOG_EMAIL_THANKS');
		}
		else
		{
			$msg = '';
		}

		// Flush the data from the session
		$app->setUserState('com_projectlog.contact.data', null);

		// Redirect if it is set in the parameters, otherwise redirect back to where we came from
		if ($project->params->get('redirect'))
		{
			$this->setRedirect($project->params->get('redirect'), $msg);
		}
		else
		{
			$this->setRedirect(JRoute::_('index.php?option=com_projectlog&view=project&id=' . $stub, false), $msg);
		}

		return true;
	}
    
    /**
	 * Method to email project contact form submission data
	 *
	 * @param   array       $data                       Form data
	 * @param   object      $project                    The current project object
     * @param   boolean     $copy_email_activated       true if copy email config option is enabled
	 *
	 * @return  JModel
	 * @since   3.3.1
	 */
	private function _sendEmail($data, $project, $copy_email_activated)
	{
        $app		= JFactory::getApplication();

        if ($project->email_to == '' && $project->manager != 0)
        {
            $project_user = JUser::getInstance($project->manager);
            $project->email_to = $project_user->get('email');
        }

        $mailfrom	= $app->getCfg('mailfrom');
        $fromname	= $app->getCfg('fromname');
        $sitename	= $app->getCfg('sitename');

        $name		= $data['contact_name'];
        $email		= JStringPunycode::emailToPunycode($data['contact_email']);
        $subject	= $data['contact_subject'];
        $body		= $data['contact_message'];

        // Prepare email body
        $prefix = JText::sprintf('COM_PROJECTLOG_ENQUIRY_TEXT', $project->name, JUri::base());
        $body	= $prefix . "\n" . $name . ' <' . $email . '>' . "\r\n\r\n" . stripslashes($body);

        $mail = JFactory::getMailer();
        $mail->addRecipient($project->email_to);
        $mail->addReplyTo(array($email, $name));
        $mail->setSender(array($mailfrom, $fromname));
        $mail->setSubject($sitename . ': ' . $subject);
        $mail->setBody($body);
        $sent = $mail->Send();

        // If we are supposed to copy the sender, do so.

        // Check whether email copy function activated
        if ($copy_email_activated == true && !empty($data['contact_email_copy']))
        {
            $copytext		= JText::sprintf('COM_PROJECTLOG_COPYTEXT_OF', $project->name, $sitename);
            $copytext		.= "\r\n\r\n" . $body;
            $copysubject	= JText::sprintf('COM_PROJECTLOG_COPYSUBJECT_OF', $subject);

            $mail = JFactory::getMailer();
            $mail->addRecipient($email);
            $mail->addReplyTo(array($email, $name));
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject($copysubject);
            $mail->setBody($copytext);
            $sent = $mail->Send();
        }

        return $sent;
	}
}
