<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

?>
<?php echo JHtml::_('bootstrap.addTab', 'projectTab', 'docs', JText::_('COM_PROJECTLOG_RELATED_DOCS', true)); ?>
    <?php
    foreach($this->docs as $doc)
    {
        $doc->date  = JHtml::date($doc->created,JText::_('DATE_FORMAT_LC2'));
        $full_path  = $this->plmedia.$doc->path;
        $rel_path   = JURI::root(true).'/media/com_projectlog/docs/'.$doc->path;

        if($doc->path && JFile::exists($full_path)):

            // Get gravatar Image 
            $doc->gravatar = projectlogHtml::getGravatar($doc->uploader_email); 
            //$delete_btn = ($this->canDo->get('projectlog.deletedoc')) ? '<div class="bt-delete-log btn btn-danger" data-doc-id="'.$doc->doc_id.'">'.JText::_('JACTION_DELETE').'</div>' : '';
            //$edit_btn   = ($this->canDo->get('projectlog.editdoc') || ($this->canDo->get('projectlog.editdoc.own') && $doc->created_by == $this->user->id)) ? '<a href="'.JRoute::_('index.php?option=com_iproperty&task=doc.edit&id='.$doc->doc_id).'" class="btn btn-info">'.JText::_('JACTION_EDIT').'</a>' : '';

            echo 
                '<div class="plitem-cnt" id="docid-'.$doc->doc_id.'">
                    '.$doc->gravatar['image'].'
                    <div class="theplitem">
                        <h5>'.$doc->title.'</h5>
                        <br/>
                        <p>'.$doc->path.' <a href="'.$rel_path.'" target="_blank"><span class="icon-download" class="hasTooltip" title="'.JText::_('COM_PROJECTLOG_DOWNLOAD').'"></span> '.JText::_('COM_PROJECTLOG_DOWNLOAD').'</a></p>
                        <p data-utime="1371248446" class="small plitem-dt">
                            '.$doc->uploader_name.' - '.$doc->date.'
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>'; 
        endif;
    }
    ?>
<?php echo JHtml::_('bootstrap.endTab'); ?>
