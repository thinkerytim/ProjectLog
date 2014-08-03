<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('ProjectlogHelper', JPATH_ADMINISTRATOR . '/components/com_projectlog/helpers/projectlog.php');

/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 */
abstract class JHtmlProject
{
	/**
	 * Get the associated language flags
	 *
	 * @param   int  $projectid  The project id to search associations
	 *
	 * @return  string  The language HTML
     * @since   3.3.1
	 */
	public static function association($projectid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_projectlog', '#__projectlog_projects', 'com_projectlog.project', $projectid))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated project items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.name as title')
				->select('l.sef as lang_sef')
				->from('#__projectlog_projects as c')
				->select('cat.title as category_title')
				->join('LEFT', '#__categories as cat ON cat.id=c.catid')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->join('LEFT', '#__languages as l ON c.language=l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (runtimeException $e)
			{
				throw new Exception($e->getMessage(), 500);

				return false;
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = JRoute::_('index.php?option=com_projectlog&task=project.edit&id=' . (int) $item->id);
					$tooltipParts = array(
						JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
								$item->language_title,
								array('title' => $item->language_title),
								true
						),
						$item->title,
						'(' . $item->category_title . ')'
					);

					$item->link = JHtml::_('tooltip', implode(' ', $tooltipParts), null, null, $text, $url, null, 'hasTooltip label label-association label-' . $item->lang_sef);
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}

	/**
	 * @param   int $value	The featured value
	 * @param   int $i
	 * @param   bool $canChange Whether the value can be changed or not
	 *
	 * @return  string	The anchor tag to toggle featured/unfeatured projects.
	 * @since   3.3.1
	 */
	public static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png', 'projects.featured', 'COM_PROJECTLOG_UNFEATURED', 'COM_PROJECTLOG_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png', 'projects.unfeatured', 'JFEATURED', 'COM_PROJECTLOG_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), null, true);
		if ($canChange)
		{
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html .'</a>';
		}

		return $html;
	}
    
    /**
	 * Custom batch filter option
	 *
	 * @return  string  The batch filter HTML
     * @since   3.3.1
	 */    
    public static function batchproject()
	{
        require_once JPATH_COMPONENT .'/models/fields/modal/project.php';
        
        // Create the copy/move options.
		$options = array(
			JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
		);
        
        $projectfield = new JFormFieldModal_Project();
        
        $batchhtml = $projectfield->callModal("batch[project_id]", '', 'project_id');
        $batchhtml .= '<div id="batch-move-copy" class="control-group radio">'
                    . JHtml::_('select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm')
                    . '</div>';
        
        return $batchhtml;
	}
    
    /**
	 * Custom associations function for logs
	 *
	 * @param   int  $logid  The log id to search associations
	 *
	 * @return  string  The language HTML
     * @since   3.3.1
	 */    
    public static function logassociation($logid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_projectlog', '#__projectlog_logs', 'com_projectlog.log', $logid, 'id', false, false))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated project items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.title as title')
				->select('l.sef as lang_sef')
				->from('#__projectlog_logs as c')
				->select('project.name as project_title')
				->join('LEFT', '#__projectlog_projects as project ON project.id = c.project_id')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->join('LEFT', '#__languages as l ON c.language = l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (runtimeException $e)
			{
				throw new Exception($e->getMessage(), 500);

				return false;
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = JRoute::_('index.php?option=com_projectlog&task=log.edit&id=' . (int) $item->id);
					$tooltipParts = array(
						JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
								$item->language_title,
								array('title' => $item->language_title),
								true
						),
						$item->title,
						'(' . $item->project_title . ')'
					);

					$item->link = JHtml::_('tooltip', implode(' ', $tooltipParts), null, null, $text, $url, null, 'hasTooltip label label-association label-' . $item->lang_sef);
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
    
    /**
	 * Custom associations function for docs
	 *
	 * @param   int  $docid  The doc id to search associations
	 *
	 * @return  string  The language HTML
     * @since   3.3.1
	 */    
    public static function docassociation($docid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_projectlog', '#__projectlog_docs', 'com_projectlog.doc', $docid, 'id', false, false))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated project items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.title as title')
				->select('l.sef as lang_sef')
				->from('#__projectlog_docs as c')
				->select('project.name as project_title')
				->join('LEFT', '#__projectlog_projects as project ON project.id = c.project_id')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->join('LEFT', '#__languages as l ON c.language = l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (runtimeException $e)
			{
				throw new Exception($e->getMessage(), 500);

				return false;
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = JRoute::_('index.php?option=com_projectlog&task=doc.edit&id=' . (int) $item->id);
					$tooltipParts = array(
						JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
								$item->language_title,
								array('title' => $item->language_title),
								true
						),
						$item->title,
						'(' . $item->project_title . ')'
					);

					$item->link = JHtml::_('tooltip', implode(' ', $tooltipParts), null, null, $text, $url, null, 'hasTooltip label label-association label-' . $item->lang_sef);
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
}
