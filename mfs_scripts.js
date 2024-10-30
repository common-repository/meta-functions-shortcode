/**
 * @author Matthias Amacher
 * @copyright 2009 Matthias Amnacher, Switzerland 
 * @license GPL 2
 * @version 2.0
 */
/**
 * 
 * @param {Object} oName
 * @param {Object} oFrame
 * @param {Object} oDoc
 * @author http://www.howtocreate.co.uk/tutorials/javascript/elementcontents
 */
function findObj( oName, oFrame, oDoc ) {
  if( !oDoc ) { if( oFrame ) { oDoc = oFrame.document; } else { oDoc = window.document; } }
  if( oDoc[oName] ) { return oDoc[oName]; } if( oDoc.all && oDoc.all[oName] ) { return oDoc.all[oName]; }
  if( oDoc.getElementById && oDoc.getElementById(oName) ) { return oDoc.getElementById(oName); }
  for( var x = 0; x < oDoc.forms.length; x++ ) { if( oDoc.forms[x][oName] ) { return oDoc.forms[x][oName]; } }
  for( var x = 0; x < oDoc.anchors.length; x++ ) { if( oDoc.anchors[x].name == oName ) { return oDoc.anchors[x]; } }
  for( var x = 0; document.layers && x < oDoc.layers.length; x++ ) {
    var theOb = findObj( oName, null, oDoc.layers[x].document ); if( theOb ) { return theOb; } }
  if( !oFrame && window[oName] ) { return window[oName]; } if( oFrame && oFrame[oName] ) { return oFrame[oName]; }
  for( var x = 0; oFrame && oFrame.frames && x < oFrame.frames.length; x++ ) {
    var theOb = findObj(oName, oFrame.frames[x], oFrame.frames[x].document ); if( theOb ) { return theOb; } }
  return null;
}

/**
 * 
 */
function on_function_select_click(){
	var f_select = findObj(select_name);
	
	var index = f_select.selectedIndex;
	var selected = f_select.options[index].text;
	
	show_function_d(selected);
}


/**
 * 
 */
function delete_onclick(){
	var f_select = findObj(select_name);
	var index = f_select.selectedIndex;
	var selected = f_select.options[index].text;
	
	delete_function_d(selected);
	
	f_select.remove(index);
}

/**
 * 
 */
function new_onclick(){
	var form = findObj(form_name);
	form.mfs_function_name.value = '';
	form.mfs_function_result.value = '';
	form.mfs_function_args.value = '';
	//form.mfs_function_name.disabled = false;
}

/**
 * Reset all form fields to empty strings
 * @param {Object} name
 * @param {Object} args
 * @param {Object} result
 */
function set_mfs_form_values(name, args, result){
	var form = findObj(form_name);
	
  	form.mfs_function_name.value = name;
  	form.mfs_function_args.value = args;
  	form.mfs_function_result.value = result;
}
/**
 * 
 * @param {Object} func_name2
 */
function show_function_d(func_name2){
	var fn = func_name2;
	
	jQuery.ajax({
		type: "post",
		dataType: "json",
		url: "admin-ajax.php",
		data: { action: 'showmfsfunction', func_name: fn, _ajax_nonce: nonce },
		success: function(obj){ 		
			var form = findObj(form_name);
			set_mfs_form_values(obj.mfs_function_name,obj.mfs_function_name,obj.mfs_function_result);			
			//form.mfs_function_name.disabled = true;
		}
	});
	
} 
/**
 * 
 * @param {Object} func_name2
 */
function delete_function_d(func_name2){
	var fn = func_name2;
	jQuery.ajax({
		type: "post",
		dataType: "json",
		url: "admin-ajax.php",
		data: { action: 'deletemfsfunction', func_name: fn, _ajax_nonce: nonce },
		success: function(obj){			
			// show first entry after delete if it exists!
			var form = findObj(form_name);
			if (obj!=null){

  				set_mfs_form_values(obj.mfs_function_name,obj.mfs_function_args,obj.mfs_function_result);
  			}
  			else {
  				set_mfs_form_values('','','');
  			}
  		}
	});
}