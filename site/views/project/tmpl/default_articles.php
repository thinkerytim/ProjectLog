<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

?>
<?php if ($this->params->get('show_articles') && count($this->item->articles)) : ?>
    <h3><?php echo JText::_('JGLOBAL_ARTICLES'); ?></h3>
    <ul class="nav nav-list pl-project-articles">
        <?php foreach ($this->item->articles as $article) :	?>
            <li>
                <?php echo JHtml::_('link', JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug)), htmlspecialchars($article->title, ENT_COMPAT, 'UTF-8'), array('target' => '_blank', 'itemprop' => 'url')); ?>
            </li>
        <?php endforeach; ?>
        <li class="divider"></li>
    </ul>
<?php endif; ?>
