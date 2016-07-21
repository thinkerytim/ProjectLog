<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>

<div class="category-list<?php echo $this->pageclass_sfx;?>">

    <?php
    $this->subtemplatename = 'items';
    echo JLayoutHelper::render('joomla.content.category_default', $this);
    ?>
    
</div>

<?php if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();
