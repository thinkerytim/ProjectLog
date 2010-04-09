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
defined('_JEXEC') or die('Restricted access');
$plog_home_link = JRoute::_('index.php?option=com_projectlog&view=cat&id='.JRequest::getVar('id'));
$add_project_link = JRoute::_('index.php?option=com_projectlog&view=cat&layout=form&cid='.JRequest::getVar('id'));
/*// use below link and class for edit/add project links for modal window use :: useful when using very narrow layout //*/
//$add_project_link = JRoute::_('index.php?option=com_projectlog&view=cat&layout=form&tmpl=component&cid='.JRequest::getVar('id'));
//remove spaces between < and ?
//<a href="< ?php echo $add_project_link; ? >" class="red modal" rel="{handler: 'iframe', size: {x: 850, y: 600}}">
?>

<?php if(PEDIT_ACCESS): ?>
<div align="right">
    <a href="<?php echo $add_project_link; ?>" class="red">[<?php echo JText::_('ADD PROJECT'); ?>]</a>
</div>
<?php endif; ?>

<script language="javascript" type="text/javascript">
	function tableOrdering( order, dir, task )
	{
		var form = document.adminForm;

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		document.adminForm.submit( task );
	}

    function listItemTask( id, task )
	{
		var form = document.adminForm;

		form.project_edit.value 	= id;
        form.task.value 	        = task;
		document.adminForm.submit( task );
	}

    function resetForm(){
        document.adminForm.search.value='';
        document.adminForm.filter.selectedIndex='';
    }
</script>

<div class="main-article-title">
    <h2 class="contentheading"><?php echo $this->catinfo->title; ?></h2>
</div>
<div class="main-article-block">
<form name="adminForm" method="get" action="index.php">
<table class="ptable" width="100%" cellpadding="5" cellspacing="1">
    <tr>
      <td colspan="2">
        <div align="left" class="prop_header_results">
            <?php if( $this->projects ) :
                    echo $this->pagination->getResultsCounter();
                  else:
                    echo '--';
                  endif;
            ?>
        </div>
      </td>
      <td colspan="5">
        <div align="right" class="prop_header_results">
             <?php echo JText::_( 'SEARCH' ).' '.$this->lists['filter']; ?>
			<input type="text" name="search" value="<?php echo $this->lists['search']; ?>" class="inputbox" onChange="document.adminForm.submit();" />
			<button onclick="document.adminForm.submit();"><?php echo JText::_( 'GO' ); ?></button>
            <button onclick="resetForm();document.adminForm.submit();"><?php echo JText::_( 'RESET' ); ?></button>
        </div>
      </td>
    </tr>
	<?php
	//display results for projects
	if( $this->projects ) :
		echo
			'<tr>
			  <th width="15%">'. JHTML::_('grid.sort',  JText::_('RELEASE NUM'), 'p.release_id', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
              <th width="20%">'. JHTML::_('grid.sort',  JText::_('PROJECT NAME'), 'p.title', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th width="15%">'. JHTML::_('grid.sort',  JText::_('PROJECT MANAGER'), 'p.manager', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th width="15%">'. JHTML::_('grid.sort',  JText::_('PROJECT LEAD'), 'p.chief', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th width="15%">'. JHTML::_('grid.sort',  JText::_('RELEASE DATE'), 'p.release_date', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th width="10%">'. JHTML::_('grid.sort',  JText::_('STATUS'), 'p.status', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
              <th width="10%">'. JHTML::_('grid.sort',  JText::_('ON SITE'), 'p.onsite', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			</tr>';

			$i = 0;
            foreach($this->projects as $p) :
                $delete_project_link = JRoute::_('index.php?option=com_projectlog&view=cat&task=deleteProject&id='.$p->id.'&category_id='.$p->category);
                $proj_link = JRoute::_('index.php?option=com_projectlog&view=project&id=' . $p->id);
                $release_date = JFactory::getDate( $p->release_date );
                
                switch( $p->status ){
                    case JText::_('IN PROGRESS'):
                        $statusclass = 'green';
                    break;
                    case JText::_('ON HOLD'):
                        $statusclass = 'orange';
                    break;
                    case JText::_('COMPLETE'):
                        $statusclass = 'red';
                    break;
                    default:
                        $statusclass = 'green';
                    break;
                }
				echo '<tr>
					  	<td align="center">' . $p->release_id . '</td>
                        <td>
                            <a href="'.$proj_link.'">' . $p->title . '</a>';
                            if(($this->user->id == $p->manager || $this->user->id == $p->created_by && PEDIT_ACCESS) || PLOG_ADMIN ):
                                echo '<br /><a href="'. $add_project_link . '&edit=' . $p->id . '" class="red">['.JText::_('EDIT').']</a>';
                                echo '<a href="'. $delete_project_link . '" class="red" onclick="if(confirm(\''.JText::_('CONFIRM DELETE').'\')){return true;}else{return false;};">['.JText::_('DELETE').']</a>';
                            endif;
                echo '
                        </td>
						<td>' . projectlogHTML::getusername($p->manager) . '</td>
						<td>' . projectlogHTML::getusername($p->chief) . '</td>
						<td align="center">' . $release_date->toFormat('%m/%e/%Y' ) . '</td>
						<td align="center">';
                            if(($this->user->id == $p->manager || $this->user->id == $p->created_by && PEDIT_ACCESS) || PLOG_ADMIN ):
                                echo '<a href="javascript:void();" onClick="return listItemTask(\''. $p->id . '\',\'changeStatus\')" class="'.$statusclass.'">' . $p->status . '</a>';
                            else:
                                echo '<span class="'.$statusclass.'">' . $p->status . '</span>';
                            endif;
                echo '
                        </td>
                        <td align="center">';
                            $task = $p->onsite ? 'projectOffsite' : 'projectOnsite';
                            $img = $p->onsite ? 'tick.png' : 'publish_x.png';

                            if(($this->user->id == $p->manager || $this->user->id == $p->created_by && PEDIT_ACCESS) || PLOG_ADMIN ):
                                echo '<a href="javascript:void();" onClick="return listItemTask(\''. $p->id . '\',\'' . $task . '\')"><img border="0" src="components/com_projectlog/assets/images/'. $img . '" alt="" /></a>';
                            else:
                                echo '<img border="0" src="components/com_projectlog/assets/images/'. $img . '" alt="" />';
                            endif;
                echo '
                        </td>
					  </tr>';
                      $i++;
                      $status;
			endforeach;

		echo
			'<tr>
				<td colspan="3" align="left">
					' . $this->pagination->getPagesLinks() . '&nbsp;
				</td>
                <td colspan="4" align="right">
                    Show: ' . $this->pagination->getLimitBox() . '
                </td>
			</tr>
            <tr>
				<td colspan="7" align="center">
					' . $this->pagination->getPagesCounter() . '
				</td>
			</tr>';
	else :

		echo
			'<tr>
			  <td colspan="7">
				<div align="center">
					'.JText::_('NO PROJECTS').'
				</div>
			  </td>
			</tr>';

	endif;
    ?>
</table>
<input type="hidden" name="option" value="com_projectlog" />
<input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="project_edit" value="" />
<input type="hidden" name="id" value="<?php echo JRequest::getVar('id'); ?>" />
<input type="hidden" name="task" value="" />
</form>
</div>
<?php
if($this->settings->get('footer')) echo '<p class="copyright">'. projectlogAdmin::footer().'</p>';
?>