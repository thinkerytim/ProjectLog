/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

function Image(i,fn,t,p)
{
	this.id 		= i;
	this.fname = fn;
	this.type	= t;
	this.path	= p;
}

function previewImageBind( list, e, base_path ) {
      form 		= document.bindform;
      srcList 	= eval( "form." + list );
      preImg 	= eval( "document." + e );
      iid			=	eval(srcList.options[srcList.selectedIndex].value);
                        
      if(list == "glimages" || list == "objimages") {
      	var img 		= aimg[iid];
      	var fileName 	= img.path + img.fname + img.type;		
      }
      else{
      	var img 		= oimg[iid];
      	var fileName 	= img.path + img.fname + img.type;	
      }
                  
      if (fileName.length == 0) {
          preImg.src 		= "../components/com_projectlog/assets/images/nopic.png";
      } else {
          preImg.src 		= base_path + fileName;
      }
 }  

function saveBindings() {
	var form = document.bindform;
			
	var l = form.objimages.length;
	var list = new Array();
				
	if(l==0) {
		alert("No Images To Bind");
	}
	else{	
		for(x=0;x<(l);x++) {
			list[x] = form.objimages.options[x].value;
		}			
		form.imglist.value = list;
		form.submit();
	}
}

function changeImages(cmd,fnr) {		
	var form = eval('document.glform' + fnr);						
			
	form.task.value = cmd;
	form.submit();					
}

function changeFiles(cmd,fnr) {		
	var form = eval('document.fform' + fnr);						
		
	form.task.value = cmd;
	form.submit();					
}
