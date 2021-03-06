<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports a modal project picker.
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class JFormFieldModal_Project extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   3.3.1
	 */
	protected $type = 'Modal_Project';
    
    /**
     * Form clear button boolean
     * 
     * @var     boolean
     * @since   3.3.1
     */
    protected $showclear = true;
    
    /**
     * Batch indicator
     * 
     * @var     boolean
     * @since   3.3.1
     */
    protected $batch = true;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   3.3.1
	 */
	protected function getInput($forcedlanguage = false)
	{
		$app            = JFactory::getApplication();
        
        $allowEdit		= ((string) $this->element['edit'] == 'true' && !isset($this->element['readonly'])) ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false' && $this->showclear === true && !isset($this->element['readonly'])) ? true : false;
        $this->element['language'] = $forcedlanguage;

		// Load language
		JFactory::getLanguage()->load('com_projectlog', JPATH_ADMINISTRATOR);

		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');
		JHtml::_('bootstrap.tooltip');

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectProject_' . $this->id . '(id, name, object) {';
		$script[] = '		document.id("' . $this->id . '_id").value = id;';
		$script[] = '		document.id("' . $this->id . '_name").value = name;';

		if ($allowEdit)
		{
			$script[] = '		jQuery("#' . $this->id . '_edit").removeClass("hidden");';
		}

		if ($allowClear)
		{
			$script[] = '		jQuery("#' . $this->id . '_clear").removeClass("hidden");';
		}

		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearProject(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_PROJECTLOG_SELECT_A_PROJECT', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_projectlog&amp;view=projects&amp;layout=modal&amp;tmpl=component&amp;function=jSelectProject_' . $this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		// Get the title of the linked item value
		if ((int) $this->value > 0)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('name'))
				->from($db->quoteName('#__projectlog_projects'))
				->where('id = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage);
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_PROJECTLOG_SELECT_A_PROJECT');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active item id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current item display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		if(!isset($this->element['readonly'])) $html[] = '<a class="modal btn hasTooltip" title="' . JHtml::tooltipText('COM_PROJECTLOG_CHANGE_PROJECT') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';

		// Edit button
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip' . ($value ? '' : ' hidden') . '" href="index.php?option=com_projectlog&layout=modal&tmpl=component&task=project.edit&id=' . $value . '" target="_blank" title="' . JHtml::tooltipText('COM_PROJECTLOG_EDIT_PROJECT') . '" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear button
		if ($allowClear)
		{
			$html[] = '<button id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearProject(\'' . $this->id . '\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
    
    /**
	 * Method to call the modal input from views with custom arguments
	 *
	 * @return  string	The getInput field input markup.
	 *
	 * @since   3.3.1
	 */ 
    public function callModal($name, $value, $id = false, $forcedlanguage = false)
    {
        JFactory::getLanguage()->load('com_projectlog', JPATH_ADMINISTRATOR);
        
        $this->name     = $name;
        $this->id       = ($id) ? $id : $name;
        $this->value    = $value;
        $this->showclear = false;
        $this->batch    = false;
        
        echo $this->getInput($forcedlanguage);
    }
}
