<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$identifier_array = array('release_id','job_id','task_id','workorder_id');
$ident_count = 0;
?>

<ul class="breadcrumb pl-project-identifiers">
    <?php foreach($identifier_array as $identifier): ?>
        <?php if ($this->project->$identifier && $this->params->get('show_'.$identifier)) : ?>
            <li>
                <b><?php echo JText::_('COM_PROJECTLOG_'.strtoupper($identifier)); ?>:</b> 
                <span class="pl-identifier-item"><?php echo $this->project->$identifier; ?></span>
                <?php $ident_count++; if($ident_count < count($identifier_array)) { ?><span class="divider">/</span><?php } ?>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<div class="clearfix"></div>