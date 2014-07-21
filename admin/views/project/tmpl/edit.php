<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$assoc = JLanguageAssociations::isEnabled();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form')))
		{
			<?php echo $this->form->getField('misc')->save(); ?>
			Joomla.submitform(task, document.getElementById('project-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projectlog&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_PROJECTLOG_NEW_PROJECT', true) : JText::_('COM_PROJECTLOG_EDIT_PROJECT', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_DETAILS'); ?></h4>
                        <?php echo $this->form->renderField('client'); ?>
                        <?php echo $this->form->renderField('project_type'); ?>
                        <?php echo $this->form->renderField('release_date'); ?>
                        <?php echo $this->form->renderField('status'); ?>
						<?php echo $this->form->renderField('image'); ?>
                    
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_CREW'); ?></h4>
						<?php echo $this->form->renderField('manager'); ?>
                        <?php echo $this->form->renderField('chief'); ?>
                        <?php echo $this->form->renderField('technicians'); ?>
                        <?php echo $this->form->renderField('onsite'); ?>
                    
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_DATES'); ?></h4>
                        <?php echo $this->form->renderField('deployment_from'); ?>
                        <?php echo $this->form->renderField('deployment_to'); ?>
                        <?php echo $this->form->renderField('contract_from'); ?>
                        <?php echo $this->form->renderField('contract_to'); ?>                        					
					</div>
					<div class="span6">
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_LOC'); ?></h4>                        
                        <?php echo $this->form->renderField('general_loc'); ?>
                        <?php echo $this->form->renderField('specific_loc'); ?>
						
                        <h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_IDENTIFIERS'); ?></h4>       
                        <?php echo $this->form->renderField('release_id'); ?>
                        <?php echo $this->form->renderField('job_id'); ?>
                        <?php echo $this->form->renderField('workorder_id'); ?>
                        <?php echo $this->form->renderField('task_id'); ?>
                        
						<h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_CONTACT'); ?></h4>   
                        <?php echo $this->form->renderField('email_to'); ?>
                        <?php echo $this->form->renderField('mobile'); ?>						
						<?php echo $this->form->renderField('webpage'); ?>
                        
						<h4><?php echo JText::_('COM_PROJECTLOG_PROJECT_FILTERS'); ?></h4>   
                        <?php echo $this->form->renderField('sortname1'); ?>
						<?php echo $this->form->renderField('sortname2'); ?>
						<?php echo $this->form->renderField('sortname3'); ?>
					</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'misc', JText::_('JGLOBAL_FIELDSET_MISCELLANEOUS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
				<div class="form-vertical">
					<?php echo $this->form->renderField('misc'); ?>
				</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php if ($assoc) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>
        
        <?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JFIELD_RULES_LABEL', true)); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>
        
        
        <?php if($this->canDo->get('projectlog.createlog', $this->item->id)): ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'logs', JText::_('COM_PROJECTLOG_LOGS', true)); ?>

                <?php if(!$this->item->id): ?>     
                    <div class="alert alert-warning center"><?php echo JText::_('COM_PROJECTLOG_SAVE_FIRST'); ?></div>
                <?php else: ?>
                    <div id="log-msg"></div>
                    <div class="log-container">
                        <?php
                        foreach($this->logs as $log)
                        {
                            $loggername     = $log->logger_name;
                            $loggeremail    = $log->logger_email;
                            $logtitle       = $log->title;
                            $logdata        = $log->description;
                            $logdate        = JHtml::date($log->created,JText::_('DATE_FORMAT_LC2'));

                            // Get gravatar Image 
                            $grav_url = projectlogHtml::getGravatar($loggeremail);                        

                            echo 
                                '<div class="log-cnt">
                                    <img src="'.$grav_url.'" />
                                    <div class="thelog">
                                        <h5>'.$logtitle.'</h5>
                                        <br/>
                                        <p>'.$logdata.'</p>
                                        <p data-utime="1371248446" class="small log-dt">'.$loggername.' - '.$logdate.'</p>
                                    </div>
                                </div>';                  
                        }
                        ?>
                        <div class="new-log-bt">
                            <span>Write log entry ...</span>
                        </div>
                        <div class="new-log-cnt">
                            <input type="text" id="title-log" name="title-log" value="" placeholder="Log Title" />
                            <textarea class="the-new-log"></textarea>
                            <p>
                                <div class="bt-add-log"><?php echo JText::_('JSUBMIT'); ?></div>
                                <div class="bt-cancel-log"><?php echo JText::_('JCANCEL'); ?></div>
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <script type="text/javascript">                   
                        (function($) {
                            $('.new-log-bt').click(function(event){    
                                $(this).hide();
                                $('.new-log-cnt').show();
                                $('#title-log').focus();
                            });

                            /* when start writing the log activate the "add" button */
                            $('.the-new-log').bind('input propertychange', function() {
                               $(".bt-add-log").css({opacity:0.6});
                               var checklength = $(this).val().length;
                               if(checklength){ $(".bt-add-log").css({opacity:1}); }
                            });

                            /* on click on the cancel button */
                            $('.bt-cancel-log').click(function(){
                                $('.the-new-log').val('');
                                $('.new-log-cnt').fadeOut('fast', function(){
                                    $('.new-log-bt').fadeIn('fast');
                                });
                            });

                            // on post log click 
                            $('.bt-add-log').click(function(){                                
                                var theTitle = $('#title-log');
                                var theLog = $('.the-new-log');

                                if( !theLog.val()){ 
                                    alert('You need to write a log!'); 
                                }else{ 
                                    //ajax request vars
                                    var logurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.addLog';
                                    var req = new Request.JSON({
                                        type: "post",
                                        url: logurl,
                                        data: {
                                            'project_id' : '<?php echo $this->item->id; ?>',
                                            'title' : theTitle.val(),
                                            'log' : theLog.val(),
                                            '<?php echo JSession::getFormToken(); ?>':'1',
                                            'format': 'raw'
                                        },
                                        onSuccess: function(r){   
                                            if (!r.success && r.message)
                                            {
                                                // Success flag is set to 'false' and main response message given
                                                // So you can alert it or insert it into some HTML element
                                                alert(r.message);
                                            }

                                            if (r.messages)
                                            {
                                                // All the enqueued messages of the $app object can simple be
                                                // rendered by the respective helper function of Joomla!
                                                // They will automatically be displayed at the messages section of the template
                                                Joomla.renderMessages(r.messages);
                                            }

                                            if (r.data)
                                            {
                                                theLog.val('');
                                                theTitle.val('');
                                                $('.new-log-cnt').hide('fast', function(){
                                                    $('.new-log-bt').show('fast');
                                                    $('.new-log-bt').before(r.data);  
                                                })
                                            }
                                            
                                        }.bind(this),
                                            onFailure: function(xhr)
                                            {
                                                // Reaching this point means that the Ajax request itself was not successful
                                                // So JResponseJson was never called
                                                alert('Ajax error');
                                            }.bind(this),
                                            onError: function(text, error)
                                            {
                                                // Reaching this point means that the Ajax request was answered by the server, but
                                                // the response was no valid JSON (this happens sometimes if there were PHP errors,
                                                // warnings or notices during the development process of a new Ajax request).
                                                alert(error + "\n\n" + text);
                                            }.bind(this)                                          
                                        });
                                        req.post();
                                }
                            });

                            // create auto complete
                            $.each(['project_type'], function(index, value){
                                var url = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.ajaxAutocomplete&format=raw&field='+value+'&<?php echo JSession::getFormToken(); ?>=1';
                                $.getJSON(url).done(function( data ){
                                    $('#jform_'+value).typeahead({source: data, items:5});
                                });
                            });
                        })(jQuery);
                    </script>
                <?php endif; ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
