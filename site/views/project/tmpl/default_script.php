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
//@todo: This should be an external js file used in both front end and admin views
// Need to majorly compress the amount of code, but for now it'll do
?>       
<script type="text/javascript">                   
    (function($) {
        <?php if($this->canDo->get('projectlog.createlog')): ?>
        $('.new-plitem-bt').click(function(event){    
            $(this).hide();
            $('.new-plitem-cnt').show();
            $('#title-plitem').focus();
        });

        /* when start writing the log activate the "add" button */
        $('.the-new-plitem').bind('input propertychange', function() {
           $(".bt-add-plitem").attr('disabled', true);
           var checklength = $(this).val().length;
           if(checklength){ $(".bt-add-plitem").attr('disabled', false); }
        });

        /* on click on the cancel button */
        $('.bt-cancel-plitem').click(function(){
            $('.the-new-plitem').val('');
            $('.new-plitem-cnt').fadeOut('fast', function(){
                $('.new-plitem-bt').fadeIn('fast');
            });
        });

        // on post log click 
        $('.bt-add-plitem').click(function(){                                
            var theTitle = $('#title-plitem');
            var theLog = $('.the-new-plitem');

            if( !theLog.val()){ 
                alert('<?php echo addslashes(JText::_('COM_PROJECTLOG_EMPTY_LOG_MSG')); ?>'); 
            }else{ 
                //ajax request vars
                var logurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.addLog';
                var req = new Request.JSON({
                    method: "post",
                    url: logurl,
                    data: {
                        'project_id' : '<?php echo $this->item->id; ?>',
                        'title' : theTitle.val(),
                        'description' : theLog.val(),
                        '<?php echo JSession::getFormToken(); ?>':'1',
                        'language' : '<?php echo $this->item->language; ?>',
                        'format': 'json'
                    },
                    onSuccess: function(r){   
                        if (!r.success && r.message)
                        {
                            // Success flag is set to 'false' and main response message given
                            // So you can alert it or insert it into some HTML element
                            $('#log-error-msg').html(r.message);
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
                            $('#log-error-msg').empty();
                            theLog.val('');
                            theTitle.val('');
                            $('.new-plitem-cnt').hide('fast', function(){
                                $('.new-plitem-bt').show('fast');
                                $('#plitem-log-container').prepend(r.data);  
                            })
                        }

                    }.bind(this),
                        onFailure: function(xhr)
                        {
                            // Reaching this point means that the Ajax request itself was not successful
                            // So JResponseJson was never called
                            $('#log-error-msg').html('Ajax error');
                        }.bind(this),
                        onError: function(text, error)
                        {
                            // Reaching this point means that the Ajax request was answered by the server, but
                            // the response was no valid JSON (this happens sometimes if there were PHP errors,
                            // warnings or notices during the development process of a new Ajax request).
                            $('#log-error-msg').html(error + "\n\n" + text);
                        }.bind(this)                                          
                    });
                    req.post();
            }
        });
        <?php endif; ?>

        <?php if($this->canDo->get('projectlog.deletelog')): ?>
        // on post log click 
        $('.bt-delete-log-plitem').click(function(){
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
                    'format': 'json'
                },
                onSuccess: function(r){   
                    if (!r.success && r.message)
                    {
                        // Success flag is set to 'false' and main response message given
                        // So you can alert it or insert it into some HTML element
                        $('#log-error-msg').html(r.message);
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
                        $('#log-error-msg').html(error + "\n\n" + text);
                    }.bind(this)                                          
                });
                req.post();
        });
        <?php endif; ?>

        <?php if($this->canDo->get('projectlog.deletedoc')): ?>
        // on post log click 
        $('.bt-delete-doc-plitem').click(function(){
            if(!confirm('<?php echo addslashes(JText::_('COM_PROJECTLOG_CONFIRM_DELETE')); ?>')){
                return false;
            }

            var docId = this.getAttribute("data-doc-id");

            //ajax request vars
            var docurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_projectlog&task=ajax.deleteDoc';
            var req = new Request.JSON({
                type: "post",
                url: docurl,
                data: {
                    'doc_id' : docId,
                    '<?php echo JSession::getFormToken(); ?>':'1',
                    'format': 'json'
                },
                onSuccess: function(r){   
                    if (!r.success && r.message)
                    {
                        // Success flag is set to 'false' and main response message given
                        // So you can alert it or insert it into some HTML element
                        $('#doc-error-msg').html(r.message);
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
                       $('#docid-'+r.data).fadeOut(300, function(){
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
                        $('#doc-error-msg').html(error + "\n\n" + text);
                    }.bind(this)                                          
                });
                req.post();
        });
        <?php endif; ?>
    })(jQuery);
</script>
