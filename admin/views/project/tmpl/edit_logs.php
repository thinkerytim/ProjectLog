<?php if($this->canDo->get('projectlog.createlog')): ?>
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
                <?php if($this->canDo->get('projectlog.createlog')): ?>
                    <div class="new-log-bt">
                        <span><?php echo JText::_('COM_PROJECTLOG_WRITE_LOG'); ?></span>
                    </div>
                    <div class="new-log-cnt">
                        <input type="text" id="title-log" name="title-log" value="" placeholder="Log Title" />
                        <textarea class="the-new-log"></textarea>
                        <p>
                            <div class="bt-add-log"><?php echo JText::_('JSUBMIT'); ?></div>
                            <div class="bt-cancel-log"><?php echo JText::_('JCANCEL'); ?></div>
                        </p>
                    </div>
                <?php endif; ?>
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
<?php endif; ?>

