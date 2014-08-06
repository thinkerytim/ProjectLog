<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');

echo JLayoutHelper::render('joomla.content.categories_default', $this);

echo $this->loadTemplate('items');

if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();
?>
</div>
