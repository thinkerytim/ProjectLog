<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$this->subtemplatename = 'items';
echo JLayoutHelper::render('joomla.content.category_default', $this);

if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();
