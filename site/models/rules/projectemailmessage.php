<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * JFormRule for com_projectlog to make sure the message body contains no banned word.
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class JFormRuleProjectEmailMessage extends JFormRule
{
	/**
	 * Method to test a message for banned words
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the field tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   JRegistry         $input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   JForm             $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
     * @since       3.3.1
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		$params = JComponentHelper::getParams('com_projectlog');
		$banned = $params->get('banned_text');

		if ($banned)
		{
			foreach(explode(';', $banned) as $item)
			{
				if ($item != '' && JString::stristr($value, $item) !== false)
				{
					return false;
				}
			}
		}

		return true;
	}
}
