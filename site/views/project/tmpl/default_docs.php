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

<?php if($this->canDo->get('projectlog.createdoc')): ?>
<a href="<?php echo JRoute::_('index.php?option=com_projectlog&task=docform.add&project_id='.$this->project->id.'&return='.$this->return_page); ?>" class="btn btn-primary">
    <span class="icon icon-edit">
        &nbsp;<?php echo JText::_('COM_PROJECTLOG_FORM_ADD_DOC'); ?>
    </span>
</a>
<?php endif; ?>
<div id="doc-error-msg"></div>    
    <div class="plitem-container" id="plitem-doc-container"> 
        <?php
        foreach($this->docs as $doc)
        {
            $doc->date  = JHtml::date($doc->created,JText::_('DATE_FORMAT_LC2'));
            $full_path  = $this->plmedia.$doc->path;
            $rel_path   = JURI::root(true).'/media/com_projectlog/docs/'.$doc->path;

            if($doc->path && JFile::exists($full_path)):

                // Get gravatar Image 
                $gravatar       = projectlogHtml::getGravatar($doc->uploader_email); 
                $download_link  = '<a href="'.$rel_path.'" target="_blank"><span class="icon-download" class="hasTooltip" title="'.JText::_('COM_PROJECTLOG_DOWNLOAD').'"></span> '.JText::_('COM_PROJECTLOG_DOWNLOAD').'</a>';
                $delete_btn     = ($this->canDo->get('projectlog.deletedoc')) ? '<a class="bt-delete-doc-plitem btn" data-doc-id="'.$doc->doc_id.'"><span class="icon icon-trash hasTooltip" title="'.JText::_('JACTION_DELETE').'">&nbsp;</span></a>' : '';
                $edit_btn       = ($this->canDo->get('projectlog.editdoc') || ($this->canDo->get('projectlog.editlog.own') && $doc->created_by == $this->user->id)) ? '<a href="'.JRoute::_('index.php?option=com_projectlog&task=docform.edit&a_id='.$doc->doc_id).'&catid='.$this->project->catid.'&return='.$this->return_page.'" class="btn"><span class="icon icon-edit hasTooltip" title="'.JText::_('JACTION_EDIT').'">&nbsp;</span></a>' : '';
                
                echo 
                    '<div class="plitem-cnt" id="docid-'.$doc->doc_id.'">
                        '.$gravatar['image'];
                        if(!$this->print){
                            echo '<div class="pull-right btn-group">'.$edit_btn.$delete_btn.'</div>';
                        }
                        echo '
                        <div class="theplitem">
                            <h5>'.$doc->title.'</h5>
                            <br/>
                            <p>'.$doc->path.' - '.$download_link.'</p>
                            <p data-utime="1371248446" class="small plitem-dt">
                                '.$doc->uploader_name.' - '.$doc->date.'
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>'; 
            endif;
        }
        ?>
    </div>
<?php echo JHtml::_('bootstrap.endTab'); ?>
