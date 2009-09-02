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

$add_project_link = 'index.php?option=com_projectlog&view=projectlog&layout=form&Itemid='. JRequest::getVar('Itemid');

if($this->user->gid >= $this->edit_access):
?>
<div align="right">
	<a href="<?php echo $add_project_link; ?>" class="red">[<?php echo JText::_('add project'); ?>]</a>
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
</script>

<div class="main-article-title">
    <h2 class="contentheading"><?php echo JText::_('Current Projects'); ?></h2>
</div>
<div class="main-article-block">
<div style="margin-bottom: 15px;">
    <strong><?php echo JText::_('Current Date'); ?>:</strong> <?php echo date('M d, Y'); ?>
</div>

<form name="adminForm" method="get" action="index.php">
<table class="ptable" width="100%" cellpadding="5" cellspacing="1">
    <tr>
      <td colspan="2">
        <div align="left" class="prop_header_results">
            <?php echo $this->pagination->getResultsCounter(); ?>
        </div>
      </td>
      <td colspan="5">
        <div align="right" class="prop_header_results">
             <?php echo JText::_( 'SEARCH' ).' '.$this->lists['filter']; ?>
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
            <button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
        </div>
      </td>      
    </tr>
	<?php		
	//display results for projects
	if( $this->projects ) :
		echo   
			'<tr>
			  <th>'. JHTML::_('grid.sort',  'Release #', 'p.release_id', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
              <th>'. JHTML::_('grid.sort',  'Project Title', 'p.title', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th>'. JHTML::_('grid.sort',  'Project Manager', 'p.manager', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th>'. JHTML::_('grid.sort',  'Project Lead', 'p.chief', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th>'. JHTML::_('grid.sort',  'Release Date', 'p.release_date', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			  <th>'. JHTML::_('grid.sort',  'Status', 'p.status', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
              <th>'. JHTML::_('grid.sort',  'Onsite', 'p.onsite', $this->lists['order_Dir'], $this->lists['order'] ) . '</th>
			</tr>';
		  
			$i = 0;
            foreach($this->projects as $p) :
                $delete_project_link = 'index.php?option=com_projectlog&view=projectlog&Itemid='. JRequest::getVar('Itemid') . '&task=deleteProject&id=' . $p->id;
                switch( $p->status ){
                    case 'In Progress':
                        $statusclass = 'green';
                    break;
                    case 'On Hold':
                        $statusclass = 'orange';
                    break;
                    case 'Complete':
                        $statusclass = 'red';
                    break;
                }
				echo '<tr>
					  	<td align="center">' . $p->release_id . '</td>
                        <td>
                            <a href="index.php?option=com_projectlog&view=project&project_id=' . $p->id . '">' . $p->title . '</a>';
                            if($this->user->id == $p->manager || $this->user->gid >= $this->edit_access ):
                                echo '<br /><a href="'. $add_project_link . '&edit=' . $p->id . '" class="red">[edit]</a>';
                                echo '<a href="'. $delete_project_link . '" class="red" onclick="if(confirm(\'Are you sure you would like to delete this project? This will also delete all logs and documents associated with the project.\')){return true;}else{return false;};">[delete]</a>';
                            endif;
                echo '
                        </td>
						<td>' . ProjectlogViewHelper::getusername($p->manager) . '</td>
						<td>' . ProjectlogViewHelper::getusername($p->chief) . '</td>
						<td align="center">' . JFactory::getDate( $p->release_date )->toFormat('%m/%e/%Y' ) . '</td>
						<td align="center"><a href="#" onClick="return listItemTask(\''. $p->id . '\',\'changeStatus\')" class="' . $statusclass . '">' . $p->status . '</a></td>
                        <td align="center">';
                            $task = $p->onsite ? 'projectOffsite' : 'projectOnsite';
                            $img = $p->onsite ? 'tick.png' : 'publish_x.png';
                echo '
                        <a href="#" onClick="return listItemTask(\''. $p->id . '\',\'' . $task . '\')"><img border="0" src="components/com_projectlog/images/'. $img . '"></a>
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
					No projects entered.
				</div>
			  </td>			
			</tr>';
	
	endif; 
    ?>
</table>
<input type="hidden" name="option" value="com_projectlog" />
<input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
<input type="hidden" name="project_edit" value="" />
<input type="hidden" name="task" value="" />
</form>
</div>
