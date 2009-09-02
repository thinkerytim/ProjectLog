<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

$add_log_link = 'index.php?option=com_projectlog&view=project&layout=form&project_id='. $this->project->id . '&Itemid='. JRequest::getVar('Itemid');
$add_doc_link = 'index.php?option=com_projectlog&view=project&layout=docform&project_id='. $this->project->id .'&Itemid='. JRequest::getVar('Itemid');

if( $this->user->gid >= $this->edit_access ):
?>
<div align="right">
	<a href="<?php echo $add_log_link; ?>" class="red">[<?php echo JText::_('add log'); ?>]</a>&nbsp;
	<a href="<?php echo $add_doc_link; ?>" class="red">[<?php echo JText::_('add document'); ?>]</a>
</div>
<?php endif; ?>
<div class="main-article-title">
    <h2 class="contentheading"><?php echo $this->project->title; ?></h2>
</div>
<div class="main-article-block">
<table width="100%" cellpadding="6">
   <tr>
		<td colspan="2" valign="top" style="border-bottom: solid 1px #ccc;">
            <strong><?php echo JText::_('Release Number'); ?>:</strong> <span class="red"><?php echo ( $this->project->release_id ) ? $this->project->release_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('Job Number'); ?>:</strong> <span class="red"><?php echo ( $this->project->job_id ) ? $this->project->job_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('Task Number'); ?>:</strong> <span class="red"><?php echo ( $this->project->task_id ) ? $this->project->task_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('Work Order Number'); ?>:</strong> <span class="red"><?php echo ( $this->project->workorder_id ) ? $this->project->workorder_id : '--N/A--'; ?></span>
		</td>
   </tr>   
	<tr>
		<td width="75%" valign="top">
            <?php if( $this->project->description ) : ?>
			<span class="content_header"><?php echo JText::_('Project Description'); ?>:</span><br />
            <?php echo $this->project->description;
                  endif;
                  if( $this->project->location_gen ) : ?><br /><br />
            <span class="content_header"><?php echo JText::_('General Location'); ?>:</span><br />
            <?php echo $this->project->location_gen;
                  endif;
                  if( $this->project->location_spec ) : ?><br /><br />
            <span class="content_header"><?php echo JText::_('Specific Location'); ?>:</span><br />
            <?php echo $this->project->location_spec;
                  endif;
                  
            if( $this->user->gid >= $this->log_access ):
                //display results for logs
                if( $this->logs) :
                         echo '<br /><br />';
                         jimport('joomla.html.pane');
                         $pane		= & JPane::getInstance('sliders');
                         echo $pane->startPane("log-pane");
                         $i = 0;
                         foreach($this->logs as $l) :
                            $delete_log_link = 'index.php?option=com_projectlog&view=project&project_id='. $this->project->id . '&Itemid='. JRequest::getVar('Itemid') . '&task=deleteLog&id=' . $l->id;
                            $edit_log_link = 'index.php?option=com_projectlog&view=project&layout=form&project_id='. $this->project->id . '&Itemid='. JRequest::getVar('Itemid').'&edit='. $l->id;

                            echo $pane->startPanel( $l->title, $l->id.$i );
                            if($this->user->id == $l->loggedby || $this->user->gid >= 24 ):
                                echo '<div align="right" style="padding-right: 5px;"><a href="' . $edit_log_link . '">'.JText::_('edit').'</a> | <a href="' . $delete_log_link . '" onclick="if(confirm(\'Are you sure you would like to delete this log item?\')){return true;}else{return false;};">delete</a></div>';
                            endif;
                            echo '<div style="padding: 10px 25px;">
                                  <div>'.JText::_('Created').' <strong>' . JFactory::getDate( $l->date )->toFormat('%b %d, %Y at %H:%M' ) . '</strong> by <strong>' . ProjectlogViewHelper::getusername( $l->loggedby ) . '</strong></div>';

                                  if( $l->modified_by ):
                                    echo '<div>'.JText::_('Modified').' <strong>' . JFactory::getDate( $l->modified )->toFormat('%b %d, %Y at %H:%M' ) . '</strong> by <strong>' . ProjectlogViewHelper::getusername( $l->modified_by ) . '</strong></div>';
                                  endif;
                            echo '<div style="margin-top: 10px;">' . $l->description . '</div>
                                  </div>';
                            echo $pane->endPanel();
                            $i++;

                        endforeach;
                        echo $pane->endPane();
                else :

                    echo
                        '<br /><br />
                         <div align="center" style="border-top: solid 1px #ff0000;">
                            ' . JText::_('No Project Logs Entered') . '
                         </div>';

                endif;
            endif;
            ?>
		</td>
        <td width="25%" rowspan="3" style="border-left: solid 1px #ccc;" valign="top">
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Release Date'); ?>:</span><br />
            <?php echo ($this->project->release_date != '0000-00-00') ? JFactory::getDate( $this->project->release_date )->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Performance Period'); ?>:</span><br />
            <?php echo ($this->project->contract_from != '0000-00-00') ? JFactory::getDate( $this->project->contract_from )->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            &nbsp;-&nbsp;
            <?php echo ($this->project->contract_to != '0000-00-00') ? JFactory::getDate( $this->project->contract_to )->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Crew Deployment'); ?>:</span><br />
            <?php echo JFactory::getDate( $this->project->deployment_from )->toFormat('%b %d, %Y' ) . ' - '
                  . JFactory::getDate( $this->project->deployment_to )->toFormat('%b %d, %Y' ); ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Project Type'); ?>:</span><br />
            <?php echo ($this->project->surveytype) ? $this->project->surveytype : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Project Manager'); ?>:</span><br />
            <?php 
				if($this->project->manager){
					echo ProjectlogViewHelper::getusername( $this->project->manager );
					$managerdetails = ProjectlogViewHelper::userDetails($this->project->manager);
					if($managerdetails){
						echo ($managerdetails->email_to) ? '<br /><a href="mailto:'.$managerdetails->email_to.'">'. $managerdetails->email_to . '</a>' : '';
						echo ($managerdetails->telephone) ? '<br />' . $managerdetails->telephone : '';
					} 
				}else{
					echo '--N/A--';
				}			
			?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Project Lead'); ?>:</span><br />
            <?php echo ($this->project->chief) ? ProjectlogViewHelper::getusername( $this->project->chief ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Client'); ?>:</span><br />
            <?php echo ($this->project->surveyor) ? $this->project->surveyor : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Technician'); ?>:</span><br />
            <?php
                if( $this->project->technicians ) :
                $cad_techs = explode(',', $this->project->technicians );
                foreach( $cad_techs as $c ):
                    echo ProjectlogViewHelper::getusername( $c ) . '<br />'; 
                endforeach;
                else:
                    echo '--N/A--';
                endif;
            ?>
            </div>
            
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('Crew On Site'); ?>:</span><br />
            <?php
                if( $this->project->onsite == 1 ){
                    $img = 'tick.png';
                    $yesno = 'Yes';
                }else{
                    $img = 'publish_x.png';
                    $yesno = 'No';
                }
                echo '<img src="components/com_projectlog/images/' . $img . '" border="0" alt="' . $yesno . '" align="absmiddle" /> ' . $yesno;
            ?>
            </div>            
            <?php
            if( $this->user->gid >= $this->doc_access ):
                if( $this->docs ) :				
                echo '<div class="right_details">';
                echo '<div class="content_header2">' . JText::_('Related Documents') . ':</div>';
                foreach( $this->docs as $d ):
                    $delete_doc_link = 'index.php?option=com_projectlog&view=project&project_id='. $this->project->id . '&Itemid='. JRequest::getVar('Itemid') . '&task=deleteDoc&id=' . $d->id;
					echo '<div class="doc_item">
							<a href="' . $this->doc_path . $d->path . '" target="_blank" class="hasTip" title="Document :: Submitted by: ' . ProjectlogViewHelper::getusername($d->submittedby) . '<br />File: ' . $d->path . '<br />Submitted Date: ' . $d->date . '">
								' . $d->name . '
							</a>';
							if($this->user->id == $d->submittedby || $this->user->gid >= $this->edit_access ):
								echo '<br /><a href="' . $delete_doc_link . '" onclick="if(confirm(\'Are you sure you would like to delete this document?\')){return true;}else{return false;};" class="red">[delete]</a>';
							endif;
					echo '</div>';
                endforeach;
                echo '</div>';
                endif;
            endif;
            ?>            
        </td>
	</tr>
    <tr>
		<td colspan="2" valign="top">&nbsp;</td>
	</tr>
</table>
<div class="padder"></div>
</div>
