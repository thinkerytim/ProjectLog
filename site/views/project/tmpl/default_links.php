<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$links = array();
$valid_links = false;

//let's check if there are valid links for this project first
foreach (range('a', 'e') as $char) :// letters 'a' to 'e'
    $link_label = $this->project->params->get('link'.$char.'_name');
    $link_url   = $this->project->params->get('link'.$char);
    
    $links[] = array('label' => $link_label, 'url' => $link_url);
    if($link_url) $valid_links = true;
endforeach;
?>

<?php if($valid_links): ?>
    <?php echo '<h3>'. JText::_('COM_PROJECTLOG_LINKS').'</h3>';  ?>
    <ul class="nav nav-list pl-project-links">
        <?php
        foreach ($links as $link) :
            if (!$link['url']) :
                continue;
            endif;

            // Add 'http://' if not present
            $link_url = (0 === strpos($link['url'], 'http')) ? $link['url'] : 'http://'.$link['url'];

            // If no label is present, take the link
            $label = ($link['label']) ? $link['label'] : $link['url'];
            ?>
            <li>
                <?php echo JHtml::_('link', $link_url, $label,array('target' => '_blank', 'itemprop' => 'url')); ?>
            </li>
        <?php endforeach; ?>
        <li class="divider"></li>
    </ul>
<?php endif; ?>
