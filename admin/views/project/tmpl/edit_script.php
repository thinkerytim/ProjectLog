<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// We only need to include this script if the user is allowed to create logs or delete logs/docs
if ($this->canDo->get('projectlog.createlog') || $this->canDo->get('projectlog.deletelog')):
    JFactory::getDocument()->addScript( JURI::root(true).'/components/com_projectlog/assets/js/projectlog.js');
    ?>  

    <script type="text/javascript">

        var createLog   = <?php echo ($this->canDo->get('projectlog.createlog')) ? 'true' : 'false'; ?>;
        var deleteLog   = <?php echo ($this->canDo->get('projectlog.deletelog')) ? 'true' : 'false'; ?>;
        var deleteDoc   = <?php echo ($this->canDo->get('projectlog.deletedoc')) ? 'true' : 'false'; ?>;
        var plOptions   = false;
        plOptions = {
            plProjectid : <?php echo (int)$this->item->id; ?>,
            plToken : '<?php echo JSession::getFormToken(); ?>',
            plBaseurl : '<?php echo rtrim(JURI::root(), '/'); ?>',
            plLanguageTag : '<?php echo $this->item->language; ?>',
            plMessages: {
                confirmDelete : '<?php echo addslashes(JText::_('COM_PROJECTLOG_CONFIRM_DELETE')); ?>',
                emptyLog: '<?php echo addslashes(JText::_('COM_PROJECTLOG_EMPTY_LOG_MSG')); ?>',
                successMsg: '<?php echo addslashes(JText::_('COM_PROJECTLOG_SUCCESSFUL')); ?>'
            },
            plClient : '<?php echo (JFactory::getApplication()->getName() == 'administrator') ? '/administrator' : ''; ?>'
        };
    </script>
    <?php
endif;
