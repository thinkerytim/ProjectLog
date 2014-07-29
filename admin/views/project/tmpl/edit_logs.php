<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'logs', JText::_('COM_PROJECTLOG_LOGS', true)); ?>

    <?php if(!$this->item->id): ?>     
        <div class="alert alert-warning center"><?php echo JText::_('COM_PROJECTLOG_SAVE_FIRST'); ?></div>
    <?php else: ?>
        <?php if($this->canDo->get('projectlog.createlog')): ?>
            <div class="new-log-bt">
                <span><?php echo JText::_('COM_PROJECTLOG_WRITE_LOG'); ?></span>
            </div>
            <div class="new-log-cnt">
                <input type="text" id="title-log" name="title-log" value="" placeholder="<?php echo JText::_('COM_PROJECTLOG_LOG_TITLE'); ?>" />
                <textarea class="the-new-log"></textarea>
                <p>      
                    <div class="btn-group">
                    <a class="bt-add-log btn btn-success" disabled><?php echo JText::_('JSUBMIT'); ?></a>
                    <a class="bt-cancel-log btn btn-danger"><?php echo JText::_('JCANCEL'); ?></a>
                    </div>
                </p>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>            
        <div class="log-container" id="log-container">            
            <?php
            foreach($this->logs as $log)
            {
                $log->date = JHtml::date($log->created,JText::_('DATE_FORMAT_LC2'));

                // Get gravatar Image 
                $log->gravatar = projectlogHtml::getGravatar($log->logger_email); 
                $delete_btn = ($this->canDo->get('projectlog.deletelog')) ? '<div class="bt-delete-log btn btn-danger" data-log-id="'.$log->log_id.'">'.JText::_('JACTION_DELETE').'</div>' : '';
                $edit_btn   = ($this->canDo->get('projectlog.editlog') || ($this->canDo->get('projectlog.editlog.own') && $log->created_by == $this->user->id)) ? '<a href="'.JRoute::_('index.php?option=com_projectlog&task=log.edit&id='.$log->log_id).'" class="btn btn-info" target="blank">'.JText::_('JACTION_EDIT').'</a>' : '';

                echo 
                    '<div class="log-cnt" id="logid-'.$log->log_id.'">
                        '.$log->gravatar['image'].'
                        <div class="pull-right btn-group">'.$edit_btn.$delete_btn.'</div>
                        <div class="thelog">
                            <h5>'.$log->title.'</h5>
                            <br/>
                            <p>'.$log->description.'</p>
                            <p data-utime="1371248446" class="small log-dt">
                                '.$log->logger_name.' - '.$log->date.'
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>';                   
            }
            ?>
            
            <div class="clearfix"></div>
        </div>

        <script type="text/javascript">                   
            (function($) {
                <?php if($this->canDo->get('projectlog.createlog')): ?>
                $('.new-log-bt').click(function(event){    
                    $(this).hide();
                    $('.new-log-cnt').show();
                    $('#title-log').focus();
                });

                /* when start writing the log activate the "add" button */
                $('.the-new-log').bind('input propertychange', function() {
                   $(".bt-add-log").attr('disabled', true);
                   var checklength = $(this).val().length;
                   if(checklength){ $(".bt-add-log").attr('disabled', false); }
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
                        alert('<?php echo addslashes(JText::_('COM_PROJECTLOG_EMPTY_LOG_MSG')); ?>'); 
                    }else{ 
                        //ajax request vars
                        var logurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.addLog';
                        var req = new Request.JSON({
                            type: "post",
                            url: logurl,
                            data: {
                                'project_id' : '<?php echo $this->item->id; ?>',
                                'title' : theTitle.val(),
                                'description' : theLog.val(),
                                '<?php echo JSession::getFormToken(); ?>':'1',
                                'language' : '<?php echo $this->item->language; ?>',
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
                                    // All the enqueued messages of the $app object can simply be
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
                                        $('#log-container').prepend(r.data);  
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
                <?php endif; ?>
    
                <?php if($this->canDo->get('projectlog.deletelog')): ?>
                // on post log click 
                $('.bt-delete-log').click(function(){
                    if(!confirm('<?php echo addslashes(JText::_('COM_PROJECTLOG_CONFIRM_DELETE')); ?>')){
                        return false;
                    }
                    
                    var logId = this.getAttribute("data-log-id");
                    
                    //ajax request vars
                    var logurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.deleteLog';
                    var req = new Request.JSON({
                        type: "post",
                        url: logurl,
                        data: {
                            'log_id' : logId,
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
                               $('#logid-'+r.data).fadeOut(300, function(){
                                   $(this).remove();
                                });
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
                });
                <?php endif; ?>

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

