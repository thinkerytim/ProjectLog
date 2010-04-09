<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

if($this->project->group_access && !PLOG_ADMIN){
    //if user is in group add project to list
    if(!projectlogHelperQuery::isGroupMember($this->project->group_access, $this->user->get('id'))){
        JError::raiseWarning( 403, JText::_('PLOG NOT AUTHORIZED'));
        return;
    }
}

$plog_home_link = JRoute::_('index.php?option=com_projectlog&view=cat&id='.$this->project->category);
$add_log_link = JRoute::_('index.php?option=com_projectlog&view=project&layout=form&id='. $this->project->id);
$add_doc_link = JRoute::_('index.php?option=com_projectlog&view=project&layout=docform&id='. $this->project->id);

$deploy_from = JFactory::getDate( $this->project->deployment_from );
$deploy_to = JFactory::getDate( $this->project->deployment_to );
$release_date = JFactory::getDate( $this->project->release_date );
$contract_from = JFactory::getDate( $this->project->contract_from );
$contract_to = JFactory::getDate( $this->project->contract_to );

?>

<div align="right">
	<a href="<?php echo $plog_home_link; ?>" class="red">[<?php echo JText::_('PROJECTS HOME'); ?>]</a>&nbsp;
    <?php if( LEDIT_ACCESS ): ?>
        <a href="<?php echo $add_log_link; ?>" class="red">[<?php echo JText::_('ADD LOG'); ?>]</a>&nbsp;
    <?php endif; ?>
    <?php if( DEDIT_ACCESS ): ?>
        <a href="<?php echo $add_doc_link; ?>" class="red">[<?php echo JText::_('ADD DOC'); ?>]</a>
    <?php endif; ?>
</div>

<div class="main-article-title">
    <h2 class="contentheading"><?php echo $this->project->title; ?></h2>
</div>
<div class="main-article-block">
<table width="100%" cellpadding="6">
   <tr>
		<td colspan="2" valign="top" style="border-bottom: solid 1px #ccc;">
            <strong><?php echo JText::_('RELEASE NUM'); ?>:</strong> <span class="red"><?php echo ( $this->project->release_id ) ? $this->project->release_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('JOB NUM'); ?>:</strong> <span class="red"><?php echo ( $this->project->job_id ) ? $this->project->job_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('TASK NUM'); ?>:</strong> <span class="red"><?php echo ( $this->project->task_id ) ? $this->project->task_id : '--N/A--'; ?></span> |
            <strong><?php echo JText::_('WORKORDER NUM'); ?>:</strong> <span class="red"><?php echo ( $this->project->workorder_id ) ? $this->project->workorder_id : '--N/A--'; ?></span>
		</td>
   </tr>   
	<tr>
		<td width="75%" valign="top">
            <?php if( $this->project->description ) : ?>
			<span class="content_header"><?php echo JText::_('DESCRIPTION'); ?>:</span><br />
            <?php echo $this->project->description;
                  endif;
                  if( $this->project->location_gen ) : ?><br /><br />
            <span class="content_header"><?php echo JText::_('GEN LOC'); ?>:</span><br />
            <?php echo $this->project->location_gen;
                  endif;
                  if( $this->project->location_spec ) : ?><br /><br />
            <span class="content_header"><?php echo JText::_('SPEC LOC'); ?>:</span><br />
            <?php echo $this->project->location_spec;
                  endif;
                  
            if( LOG_ACCESS ):
                //display results for logs
                if( $this->logs) :
                         echo '<br /><br />';
                         jimport('joomla.html.pane');
                         $pane		= & JPane::getInstance('sliders');
                         echo $pane->startPane("log-pane");
                         $i = 0;
                         foreach($this->logs as $l) :
                            $ldate = JFactory::getDate( $l->date );
                            $lmod = JFactory::getDate( $l->modified );
                            $delete_log_link = JRoute::_('index.php?option=com_projectlog&view=project&project_id='. $this->project->id . '&task=deleteLog&id=' . $l->id);
                            $edit_log_link = JRoute::_('index.php?option=com_projectlog&view=project&layout=form&id='. $this->project->id . '&edit='. $l->id);

                            echo $pane->startPanel( $l->title, $l->id.$i );
                            if(($this->user->id == $l->loggedby && LEDIT_ACCESS) || PLOG_ADMIN ):
                                echo '<div align="right" style="padding-right: 5px;"><a href="' . $edit_log_link . '">'.JText::_('EDIT').'</a> | <a href="' . $delete_log_link . '" onclick="if(confirm(\''.JText::_('CONFIRM DELETE').'\')){return true;}else{return false;};">'.JText::_('DELETE').'</a></div>';
                            endif;
                            echo '<div style="padding: 10px 25px;">
                                  <div>'.JText::_('CREATED').' <strong>' . $ldate->toFormat('%b %d, %Y at %H:%M' ) . '</strong> by <strong>' . projectlogHTML::getusername( $l->loggedby ) . '</strong></div>';

                                  if( $l->modified_by ):
                                    echo '<div>'.JText::_('MODIFIED').' <strong>' . $lmod->toFormat('%b %d, %Y at %H:%M' ) . '</strong> by <strong>' . projectlogHTML::getusername( $l->modified_by ) . '</strong></div>';
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
                            ' . JText::_('NO PROJECT LOGS') . '
                         </div>';

                endif;
            endif;
            ?>
		</td>
        <td width="25%" rowspan="3" style="border-left: solid 1px #ccc;" valign="top">
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('RELEASE DATE'); ?>:</span><br />
            <?php echo ($this->project->release_date != '0000-00-00') ? $release_date->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('CONTRACT FROM TO'); ?>:</span><br />
            <?php echo ($this->project->contract_from != '0000-00-00') ? $contract_from->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            &nbsp;-&nbsp;
            <?php echo ($this->project->contract_to != '0000-00-00') ? $contract_to->toFormat('%b %d, %Y' ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('DEPLOYMENT FROM TO'); ?>:</span><br />
            <?php echo $deploy_from->toFormat('%b %d, %Y' ) . ' - ' . $deploy_to->toFormat('%b %d, %Y' ); ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('PROJECT TYPE'); ?>:</span><br />
            <?php echo ($this->project->projecttype) ? $this->project->projecttype : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('PROJECT MANAGER'); ?>:</span><br />
            <?php 
				if($this->project->manager){
					echo projectlogHTML::getusername( $this->project->manager );
					$managerdetails = projectlogHTML::userDetails($this->project->manager);
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
            <span class="content_header"><?php echo JText::_('PROJECT LEAD'); ?>:</span><br />
            <?php echo ($this->project->chief) ? projectlogHTML::getusername( $this->project->chief ) : '--N/A--'; ?>
            </div>
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('TECHNICIAN'); ?>:</span><br />
            <?php
                if( $this->project->technicians ) :
                $cad_techs = explode(',', $this->project->technicians );
                foreach( $cad_techs as $c ):
                    echo projectlogHTML::getusername( $c ) . '<br />';
                endforeach;
                else:
                    echo '--N/A--';
                endif;
            ?>
            </div>
            
            <div class="right_details">
            <span class="content_header"><?php echo JText::_('CREW ON SITE'); ?>:</span><br />
            <?php
                if( $this->project->onsite == 1 ){
                    $img = 'tick.png';
                    $yesno = JText::_('YES');
                }else{
                    $img = 'publish_x.png';
                    $yesno = JText::_('NO');
                }
                echo '<img src="components/com_projectlog/assets/images/' . $img . '" border="0" alt="' . $yesno . '" align="absmiddle" /> ' . $yesno;
            ?>
            </div>            
            <?php
            if( DOC_ACCESS ):
                if( $this->docs ) :				
                echo '<div class="right_details">';
                echo '<div class="content_header2">' . JText::_('RELATED DOCS') . ':</div>';
                foreach( $this->docs as $d ):
                    $delete_doc_link = JRoute::_('index.php?option=com_projectlog&view=project&project_id='. $this->project->id . '&task=deleteDoc&id=' . $d->id);
					echo '<div class="doc_item">
							<a href="' . $this->doc_path . $d->path . '" target="_blank" class="hasTip" title="'.JText::_('DOCUMENT').' :: '.JText::_('SUBMITTED BY').': ' . projectlogHTML::getusername($d->submittedby) . '<br />'.JText::_('FILE').': ' . $d->path . '<br />'.JText::_('SUBMITTED DATE').': ' . $d->date . '">
								' . $d->name . '
							</a>';
							if(($this->user->id == $d->submittedby && DEDIT_ACCESS) || PLOG_ADMIN ):
								echo '<br /><a href="' . $delete_doc_link . '" onclick="if(confirm(\''.JText::_('CONFIRM DELETE').'\')){return true;}else{return false;};" class="red">['.JText::_('DELETE').']</a>';
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
</div>
<?php if($this->settings->get('footer')) echo '<p class="copyright">'. projectlogAdmin::footer().'</p>'; ?>
