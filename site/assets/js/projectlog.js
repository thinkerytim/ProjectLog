/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

jQuery(function($) {
	$(document).ready(function(){
        
        if ( createLog ){
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
                    alert(plOptions.plMessages.emptyLog); 
                }else{ 
                    //ajax request vars
                    var logurl = plOptions.plBaseurl+plOptions.plClient+'/index.php?option=com_projectlog&task=ajax.addLog&'+plOptions.plToken+'=1';
                    var req = new Request.JSON({
                        method: "post",
                        url: logurl,
                        data: {
                            'project_id' : plOptions.plProjectid,
                            'title' : theTitle.val(),
                            'description' : theLog.val(),
                            'language' : plOptions.plLanguageTag,
                            'format': 'json'
                        },
                        onSuccess: function(r){   
                            if (!r.success && r.message)
                            {
                                // Success flag is set to 'false' and main response message given
                                // So you can alert it or insert it into some HTML element
                                $('#log-error-msg').html(r.message+' plError111');
                            }

                            if (r.messages)
                            {
                                // All the enqueued messages of the $app object can simply be
                                // rendered by the respective helper function of Joomla!
                                // They will automatically be displayed at the messages section of the template
                                Joomla.renderMessages(r.messages+' pl112');
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
                                $('#log-error-msg').html('Ajax error plError113');
                            }.bind(this),
                            onError: function(text, error)
                            {
                                // Reaching this point means that the Ajax request was answered by the server, but
                                // the response was no valid JSON (this happens sometimes if there were PHP errors,
                                // warnings or notices during the development process of a new Ajax request).
                                $('#log-error-msg').html(error + "\n\n plError114 \n\n" + text);
                            }.bind(this)                                          
                        });
                    req.post();
                }
            });
        }
        
        if ( deleteLog ){        
            // on delete log click 
            $('.bt-delete-log-plitem').click(function(){
                if(!confirm(plOptions.plMessages.confirmDelete)){
                    return false;
                }

                var logId = this.getAttribute("data-log-id");

                //ajax request vars
                var logurl = plOptions.plBaseurl+plOptions.plClient+'/index.php?option=com_projectlog&task=ajax.deleteLog&'+plOptions.plToken+'=1';
                var req = new Request.JSON({
                    type: "post",
                    url: logurl,
                    data: {
                        'log_id' : logId,
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
        }
        
        if ( deleteDoc ){
            // on delete log click 
            $('.bt-delete-doc-plitem').click(function(){
                if(!confirm(plOptions.plMessages.confirmDelete)){
                    return false;
                }

                var docId = this.getAttribute("data-doc-id");

                //ajax request vars
                var docurl = plOptions.plBaseurl+plOptions.plClient+'/index.php?option=com_projectlog&task=ajax.deleteDoc&'+plOptions.plToken+'=1';
                var req = new Request.JSON({
                    type: "post",
                    url: docurl,
                    data: {
                        'doc_id' : docId,
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
        }
	});
});