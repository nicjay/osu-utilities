function addUnits(value, tag) 
{
	if(tag.match(/menu_width/i) && value.match(/\d+/i)) {
		value = value + "px";
	} 
	if(tag.match(/menu_font_size/i)) {
		value = value + "px";
	} 

	return value;
}

function removeUnits(value, tag)
{
	var val;
	var stringValue = String(value);
	if(val = stringValue.match(/(\d+)px/)) {
		stringValue = val[1];
	} 
	return stringValue;
}

function dump(arr,level) 
{
	var dumped_text = "";
	if(!level) level = 0;

	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";

	if(typeof(arr) == 'object') { //Array/Hashes/Objects
		for(var item in arr) {
			var value = arr[item];

			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Strings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}


/* 
 * renderTree Helper Functions 
 */
function indent(depth, tag){
   var indent = "";
   var spaces = "   "; // number of spaces per indent;
   var i = 0;
   depth = parseInt(depth, 10);
      
   if(tag == 'li'){
      for(i = 0; i < depth + (depth - 1); i++){
         indent += spaces;
      }
   } else {
      for(i = 0; i < (depth + depth); i++){
         indent += spaces;
      }         
   }
   return indent;
}

/* return TRUE if current LI is the last one in the group */
function checkLast(nest, i) 
{
  lastId = 0;
  lastFlag = false;
  curDepth = nest[i].depth;
  nextDepth = (nest[i+1] != undefined) ? nest[i+1].depth : 1;

  if (nextDepth < curDepth) { lastFlag = true; }
  for(var key in nest) {
    if(nest[key].depth == 1) {
     lastId = key;  
    }
  }  
  if (lastId == i) { lastFlag = true; }
  
  return lastFlag; 
}

/*
-- param
   menu_class   : name of class that is applied to the menu container   
	 menu_id : 
*/
function renderTree(nest,params){
   var output = ""; 
   var diff;
   var nextDepth = 0;
   var classes;
   
   output += "<div";
	 if (params.menu_id) {
		 output += " id='" + params.menu_id + "'";
	 }
	 if (params.menu_class) {
		 output += " class='" + params.menu_class + "'";
	 }
	 output += ">\n";
   output += "<ul>\n";
   
   for(var i = 1; i < nest.length; i++){
      classes = '';
      curDepth = nest[i].depth;
      nextDepth = (nest[i+1] != undefined) ? nest[i+1].depth : 1;

      classes += (nest[i].active == 1) ? "active " : "";
      classes += (nextDepth > curDepth) ? "has-sub " : "";
      classes += checkLast(nest, i) ? "last " : "";      
      classes = classes.replace(/\s$/g, ""); // remove space at end
            
      output += indent(curDepth,"li") + "<li ";
      output += (classes) ? "class='" + classes + "'" : "";
      output += (nest[i].link) ? "><a href='" + nest[i].link + "' " : "><a href='#' " ;
      output += (nest[i].target == 1) ? "target='_blank' " : "";
      output += ">";
      output += "<span>" + nest[i].title + "</span></a>"; 
      output = output.replace(/ >/ig, ">"); // Remove white space
      
      if (nest[i].depth < nextDepth){
      	output += "\n" + indent(curDepth, 'ul') + "<ul>\n";
      } else if (nest[i].depth == nextDepth) {
      	output += "</li>\n";
      } else {
      	for (var j = curDepth; j > nextDepth; j--) {
            output += (j == curDepth) ?  "</li>\n" : indent(j, 'li') + "</li>\n";         	
         	output += indent(j-1, 'ul') + "</ul>\n";
      	}
      	output += indent(nextDepth, 'li') + "</li>\n";
      }      
   }
   output += "</ul>\n";
   output += "</div>";
   return output;
}


/* 
 * Create array of all tags and their default values that are present in the CSS
 */
function findCSSTags(css) 
{
	var tagPattern = /\[\[.+?\]\]/gi;
	var tagsFound = Array();

	while((n = tagPattern.exec(css)) != null) {
		var tag = n[0].match(/\[\[\s*(\w+)/i);
		var value = n[0].match(/:\s*([\w#]+)\s*\]\]/i);
		tag = tag[1];
		if(value) {
			value = value[1];
		}
		tagsFound[tag] = value;
	}
	if(css.match(/\.align-center/i)) {
			tagsFound['menu_align'] = "left";
			tagsFound['menu_align_center'] = "";
	}
	if(css.match(/\.align-right/i)) {
			tagsFound['menu_align'] = "left";
			tagsFound['menu_align_right'] = "";			
	}

	return tagsFound;
}


/*
css
-- css from drupal database.

params
-- menuClass   : name of class that will replace the #menu_class# tag in the css
-- includePath : path to include files that will replace #include_path# in the css
-- menu_width : 
*/

function renderCSS(css, params){
   var output = unescape(css);	 
	 var foundTags = findCSSTags(css);
	 var replaceValue;
	 
	 /* Loop through tags present in CSS */
	 for(tag in foundTags) {
		 if(params.hasOwnProperty(tag)) {
			 replaceValue = params[tag];
		 } else {
			 replaceValue = foundTags[tag];
		 }

		 var regex = new RegExp("\\[\\[" + tag + ".+?\\]\\]","gi"); 
		 output = output.replace(regex, replaceValue);
	 }

   output = output.replace(/#menu_class#/ig, params.menuClass);
   output = output.replace(/\[\[menu_class\]\]/ig, params.menuClass);   
   output = output.replace(/#cssmenu/ig, params.menuClass);   
   
   output = output.replace(/#include_path#/ig, params.includePath);
   output = output.replace(/\[\[include_path\]\]/ig, params.includePath);   
   return output;
}


/*
-- jQuery from drupal database.
params
-- menuClass   : name of class that will replace the #menu_class# tag in the css
*/
function renderJquery(jquery, params)
{
  var output = jquery
  output = output.replace(/#menu_class#/ig, params.menuClass);
  output = output.replace(/\[\[menu_class\]\]/ig, params.menuClass);   
  output = output.replace(/#cssmenu/ig, params.menuClass);   
  
  return output;
}


/* Return array of every menu setting available */
function getAllSettings()
{
	var tags = Array();
	tags['main_color'] = '';
	tags['menu_width'] = '';
	tags['menu_align'] = '';
	tags['menu_font_size'] = '';
	tags['menu_text_color'] = '';
	tags['menu_text_hover_color'] = '';
	tags['menu_background_color'] = '';
	tags['menu_border_color'] = '';	
	tags['sub_menu_width'] = '';
	tags['sub_menu_font_size'] = '';
	tags['sub_menu_text_color'] = '';
	tags['sub_menu_text_hover_color'] = '';
	tags['sub_menu_background_color'] = '';
	tags['sub_menu_border_color'] = '';	
	
	return tags;
}

function getSampleTriple(){
   var array = new Array();
   array[0] = { 'item_id' : "root", 'parent_id' : "none", 'depth' : "0", 'left' : "1", 'right' : "22", 'title' : "Home", 'link' : "", 'target' : "0", 'active' : "0"};
   array[1] = { 'item_id' : "1", 'parent_id' : "root", 'depth' : "1", 'left' : "2", 'right' : "3", 'title' : "Home", 'link' : "", 'target' : "0", 'active' : "0"};
   array[2] = { 'item_id' : "2", 'parent_id' : "root", 'depth' : "1", 'left' : "4", 'right' : "17", 'title' : "Products", 'link' : "", 'target' : "0", 'active' : 1};
   array[3] = { 'item_id' : "3", 'parent_id' : "2", 'depth' : "2", 'left' : "5", 'right' : "10", 'title' : "Product 1", 'link' : "", 'target' : "0", 'active' : "0"};
   array[4] = { 'item_id' : "7", 'parent_id' : "3", 'depth' : "3", 'left' : "6", 'right' : "7", 'title' : "Sub Product", 'link' : "", 'target' : "0", 'active' : "0"};
   array[5] = { 'item_id' : "8", 'parent_id' : "3", 'depth' : "3", 'left' : "8", 'right' : "9", 'title' : "Sub Product", 'link' : "", 'target' : "0", 'active' : "0"};
   array[6] = { 'item_id' : "4", 'parent_id' : "2", 'depth' : "2", 'left' : "11", 'right' : "16", 'title' : "Product 2", 'link' : "", 'target' : "0", 'active' : "0"};
   array[7] = { 'item_id' : "9", 'parent_id' : "4", 'depth' : "3", 'left' : "12", 'right' : "13", 'title' : "Sub Product", 'link' : "", 'target' : "0", 'active' : "0"};
   array[8] = { 'item_id' : "10", 'parent_id' : "4", 'depth' : "3", 'left' : "14", 'right' : "15", 'title' : "Sub Product", 'link' : "", 'target' : "0", 'active' : "0"};
   array[9] = { 'item_id' : "5", 'parent_id' : "root", 'depth' : "1", 'left' : "18", 'right' : "19", 'title' : "About", 'link' : "", 'target' : "0", 'active' : "0"};
   array[10] = { 'item_id' : "6", 'parent_id' : "root", 'depth' : "1", 'left' : "20", 'right' : "21", 'title' : "Contact", 'link' : "", 'target' : "0", 'active' : "0"};
   return array;
}

/* pre-defined drop down structre for example displays */
function getSampleDouble(){
   var array = new Array();
	 array[0] = {'item_id' : '', 'parent_id' : '', 'depth' : "0", 'left' : "1", 'right' : "20", 'title' : "Home", 'link' : "", 'target' : "0", 'active' : "1"};
	 array[1] = {'item_id' : '', 'parent_id' : '', 'depth' : "1", 'left' : "2", 'right' : "3", 'title' : "Home", 'link' : "", 'target' : "0", 'active' : "1"};
	 array[2] = {'item_id' : "2", 'parent_id' : '','depth' : "1", 'left' : "4", 'right' : "11", 'title' : "Products", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[3] = {'item_id' : "3", 'parent_id' : "2", 'depth' : "2", 'left' : "5", 'right' : "6", 'title' : "Product 1", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[4] = {'item_id' : "4", 'parent_id' : "2", 'depth' : "2", 'left' : "7", 'right' : "8", 'title' : "Product 2", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[5] = {'item_id' : "9", 'parent_id' : "2", 'depth' : "2", 'left' : "9", 'right' : "10", 'title' : "Product 3", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[6] = {'item_id' : "5", 'parent_id' : '','depth' : "1", 'left' : "12", 'right' : "17", 'title' : "About", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[7] = {'item_id' : "7", 'parent_id' : "5", 'depth' : "2", 'left' : "13", 'right' : "14", 'title' : "Company", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[8] = {'item_id' : "8", 'parent_id' : "5", 'depth' : "2", 'left' : "15", 'right' : "16", 'title' : "Contact", 'link' : "", 'target' : "0", 'active' : "0"};
	 array[9] = {'item_id' : "6", 'parent_id' : '', 'depth' : "1", 'left' : "18", 'right' : "19", 'title' : "Contact", 'link' : "", 'target' : "0", 'active' : "0"};
   return array;
}

/* pre-defined drop down structre for example displays */
function getSampleSingle(){
   var array = new Array();
   array[0] = {'item_id' : "root", 'parent_id' : "none", 'depth' : "0", 'left' : "1", 'right' : "14", 'title' : "Home", 'link' : "", 'active' : 1};
   array[1] = {'item_id' : "1", 'parent_id' : "root",  'depth' : "1",  'left' : "2",  'right' : "7",  'title' : "Home",  'link' : "", 'active' : 1};
   array[2] = {'item_id' : "2", 'parent_id' : "root", 'depth' : "1", 'left' : "8", 'right' : "9", 'title' : "Products", 'link' : ""};
   array[3] = {'item_id' : "5", 'parent_id' : "root", 'depth' : "1", 'left' : "10", 'right' : "11", 'title' : "Company", 'link' : ""};
   array[4] = {'item_id' : "6", 'parent_id' : "root", 'depth' : "1", 'left' : "12", 'right' : "13", 'title' : "Contact", 'link' : ""};
   return array;
}


;
(function ($) {


/* 
 * Update the visible menu for preview
 */ 
function previewMenu(menuObject)
{
	var menuWidth = 0;
	var previewWidth = $(".preview-container").width();
	var currentTags = findCSSTags(menu_css);
	var menuSettings = Array();
	var jquerySettings = Array();  
	var parser = new(less.Parser);	

		
	/* CSS */	
	for(tag in currentTags) {
		menuSettings[tag] = $("input[name='" + tag + "'], select[name='" + tag + "']").val();
		menuSettings[tag] = addUnits(menuSettings[tag], tag);
	}
	menuSettings['menuClass'] = "#cssmenu";
	menuSettings['includePath'] = root_path + '/sites/default/files/menu/'+menu_id+"/";
	if (menuSettings['menu_align'] == 'right' || menuSettings['menu_align'] == 'center') {
		var menu_class = 'align-' + menuSettings['menu_align'];
	} else {
		var menu_class = '';
	}
	var css = renderCSS(menu_css, menuSettings);
 	parser.parse(css, function (err, tree) {
 	    if (err) { return console.error(err) }
			$("#menu-builder .preview-includes").html("<style>" + tree.toCSS() + "</style>");
 	});
  
  /* jQuery */	
  jquerySettings['menuClass'] = "#cssmenu";
  var renderedJquery = renderJquery(raw_jquery, jquerySettings);
  
		
	/* HTML */
	arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});	 	 		 
		
	var html =renderTree(arraied,{
	  'menu_id'  : 'cssmenu',
		'menu_class' : menu_class
	});   
	$("#menu-builder .preview-container div.preview-menu").html(html).each(function() {
	  $('.preview-container a').not('.menu-image').click(function(event){
	     event.preventDefault();
	     return false;
	  });
	});
	$("#menu-builder .preview-container div.preview-html textarea").text(html);
	//var html_escaped = html.replace(/</gi,"&lt;").replace(/>/gi,"&gt;");
	//$("#menu-builder .preview-container div.preview-html code").html(html_escaped);
	//Drupal.prettify.prettifyBlock($("#menu-builder .preview-container div.preview-html code"));
	//var $content = $("#menu-builder .preview-container div.preview-html");
	//$content.syntaxHighlight();
	
	 
	/* CSS */
	var previewMenuSettings = menuSettings;
	previewMenuSettings['includePath'] = "";
	var preview_css = renderCSS(menu_css, previewMenuSettings);   

	parser.parse(preview_css, function (err, tree) {
	if (err) { return console.error(err) }
		$("#menu-builder .preview-container div.preview-css textarea").val(tree.toCSS());
	});
   
  /* Update Array */   
  arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
  $("#menu-builder .preview-container div.preview-array textarea").val(dump(arraied));

  /* Jquery */
	if (uses_jquery) { 
		$("#menu-builder .preview-container div.preview-jquery textarea").val(renderedJquery);
		
		//raw_jquery = "(function ($) {" + raw_jquery;
		//raw_jquery =  raw_jquery + "})(jQuery);";
    //console.log(renderedJquery);
    
		eval(renderedJquery);		
	}

}

function clearUpdateForm()
{
  $("#update-item input[name='item_id']").val("");
  $("#update-item input[name='title']").val("");
  $("#update-item input[name='link']").val("");      
  $("#update-item input[name='active']").attr('checked', false);
  $("#update-item input[name='target']").attr('checked', false);
  return true;
}

/* 
 * item : LI item from sortable list
 */
function populateUpdateForm(item)
{
  $("#update-item-form input[name='item_id']").val(item.id);
  $("#update-item-form input[name='title']").val(item.title);
  $("#update-item-form input[name='link']").val(item.link);      
  if(item.active == 1){
     $("#update-item-form input[name='active']").attr('checked', true);
  } else {
     $("#update-item-form input[name='active']").attr('checked', false);      
  }
  if(item.target == 1){
     $("#update-item-form input[name='target']").attr('checked', true);
  } else {
     $("#update-item-form input[name='target']").attr('checked', false);      
  }	
	
}



/* 
 * Binds our functionality to the Menu Structure
 */
function sortableFunctionality(sortableobj)
{
   $("ol.sortable li").click(function(event){
      event.stopPropagation();
      var item = getItem($(this));

      $("ol.sortable li").removeClass('active');
      $(this).addClass('active');
			
			populateUpdateForm(item);
			
			/* Check for level 1 item */
			if($(this).parent("ol.sortable").length) {
				if(menu_has_active) {
					$("#update-item-form .title .sub").show();
				}				
			} else {
				$("#update-item-form .title .sub").hide();
			}
   });
  
  $('ol.sortable').bind( "sortstop", function(event, ui) {
    previewMenu();
  });
     
   /* Remove */
   $('ol.sortable .remove').click(function(event){
      event.preventDefault();
      $(this).closest("li").remove();
      previewMenu();
      if ($('ol.sortable li.active').length == 0) {
        clearUpdateForm();
      }
   });
}


// Takes an LI inside of sortable and returns an object.params
function getItem(listItem)
{   
   menu_item = new Object();
   menu_item.id = listItem.attr("id");
   menu_item.title = listItem.attr("data-title");
   menu_item.link = listItem.attr("data-link");
   menu_item.target =  (listItem.attr("data-target") == 1) ? 1 : 0;
   menu_item.active =  (listItem.attr("data-active") == 1) ? 1 : 0;
   return menu_item;
}


/*
 * Takes an objects and sets the attr for an LI inside sortable.
 */
function setItem(menu_item)
{
	var displayTitle = "";
	if (menu_item.title.length > 15) {
	  displayTitle = menu_item.title.substr(0,15) + "...";
	} else if (!menu_item.title) {
		displayTitle = "&nbsp";
	} else {
	  displayTitle = menu_item.title;
	}

	 $("ol.sortable #" + menu_item.id).attr('data-title', menu_item.title);
	 $("ol.sortable #" + menu_item.id).attr('data-link', menu_item.link);
	 $("ol.sortable #" + menu_item.id).attr('data-active', menu_item.active);
	 $("ol.sortable #" + menu_item.id).attr('data-target', menu_item.target);       
	 $("ol.sortable #" + menu_item.id + " > div").html('<span class="disclose"><span></span></span>' + displayTitle + "<a href='#' class='remove'>X</a>");

	 sortableFunctionality();
}


/*  
 * Ajax call to save menu. Param perm = 0 will only update 
 * the menu in the DB but not permentaly save 
 */
function saveMenu(save_state)
{
  var id = mid;
  var title = $("#save-menu-form input[name='menu_save_name']").val();      
  var structure = $("#menu-struct").html();      
  var loadUrl = "/builder-save";	
	var currentTags = findCSSTags(menu_css);
	var params = {};
	
  if(!save_state){
    save_state = 0;
  }

	for(tag in currentTags) {
		params[tag] = $("input[name='" + tag + "']").val();
	}
	params['id'] = mid;
	params['title'] = title;
	params['structure'] = structure;	
	params['save_state'] = save_state;
	params['current_uid'] = current_uid;
	
	console.log(params);
  $.post(
    loadUrl,
    params,  
    function(responseText){
    	console.log(responseText);
    }
  );

  return true;
}


/* 
 * Create a menu Item for the Edit Item form and then pass it to setItem() 
 */
function updateItem(itemForm)
{
	
  menu_item = new Object();
  menu_item.id = $("input[name='item_id']", itemForm).val();
  menu_item.title = $("input[name='title']", itemForm).val();
  menu_item.link = $("input[name='link']", itemForm).val();
  menu_item.target =  ($("input[name='target']", itemForm).is(':checked')) ? 1 : 0;
  menu_item.active =  ($("input[name='active']", itemForm).is(':checked')) ? 1 : 0;
	if(menu_item.id) {
		setItem(menu_item);	
	}         
	
}



/*****************/
/*   SETTINGS    */
/*****************/


/* 
 * Load Settings from DB
 */
function initSettings(callback, menu) 
{
	var availSettings = findCSSTags(menu_css);
	var allSettings = getAllSettings();
	//var url = "/builder/get-menu-json/" + mid;

	/* Load menu settings from DB into inputs */
	for(setting in availSettings) {
		if(setting in allSettings) { 
			if(menu[setting]) { // DB Value
				$("input[name='" + setting + "'], select[name='" + setting + "']").val(menu[setting]);	
			} else { // Default Value
				$("input[name='" + setting + "'], select[name='" + setting + "']").val(removeUnits(availSettings[setting], setting));	
			}				
		}	
	}
	setSettings(availSettings, allSettings);	
	callback(menu);
}

/*
 * Hide, Show and Update settings forms
 */
function setSettings(availSettings, allSettings)
{
	this.menu_settings_visible = 0;
	this.sub_menu_settings_visible = 0;
	var parentObject = this;
	
	/* Hide Auto/Pixel select for Vertical Menus */
	if(('menu_width' in availSettings) && availSettings['menu_width'].match(/px/i)) {
		$("#menu_width_element select").hide();
	}
	
	/* menu_width */
	if(availSettings['menu_width']) {		
		if($("input[name='menu_width']").val() == 'auto') {
			$("#menu_width_element select").val('auto');
			$("#menu_width_element .units").hide();
			$("#menu_width_element input").hide();			
		} else {
			$("#menu_width_element select").val('pixels');			
			$("#menu_width_element input").show();
		}
	}
	$("select[name='menu_width_unit']").change(function(){
		if($(this).val() == 'auto') {
			$("#menu_width_element input[type='text']").hide();
			$("#menu_width_element .units").hide();
			$("#menu_width_element input[type='text']").val('auto');
		}  else {
			$("#menu_width_element input[type='text']").show();
			$("#menu_width_element .units").show();
			$("#menu_width_element input[type='text']").val('');
		}
	});	
	
	/* Menu Align */
	if(!('menu_align_center' in availSettings)) {
		$("select[name='menu_align'] option[value='center']").remove();
	}
	if(!('menu_align_right' in availSettings)) {
		$("select[name='menu_align'] option[value='right']").remove();
	}
	
	/* Main Color */	
	if(availSettings['main_color']) {	
		var color = $("#menu-color input[name='main_color']").val();		
		$("#menu-color .trigger span").css('backgroundColor', color);				
		$("#menu-color .trigger").ColorPicker({
			color	: color,
      onChange: function (hsb, hex, rgb) {
      	$("#menu-color .trigger span").css('backgroundColor', '#' + hex);
				$("#menu-color input").val("#" + hex);
      },
			onSubmit: function(hsb, hex, rgb) {
				previewMenu();
			},
      onHide: function (colpkr) {
				previewMenu();
			},			
		});				
	} else {
		$("#menu-color").hide();
	}
	
	/* Colors */
	$(".setting-item.color-picker").each(function(index){
		var options = new Object();
		var setting = $(this).children("input[type='text']").attr('name');
		var color = $(this).children("input[name='" + setting + "']").val();
		var preview =  $(this);
		
		$(this).find(".trigger span").css('backgroundColor', color);		
		$(this).children(".trigger").ColorPicker({
			color	: color,
      onChange: function (hsb, hex, rgb) {
      	preview.find(".trigger span").css('backgroundColor', '#' + hex);
				preview.find("input").val("#" + hex);
      }
		}); 
	});

	/* Hide inactive settings */
	for(tag in allSettings) {
		if(!availSettings[tag]) {
			$("#" + tag + "_element").addClass("inactive").hide();
		} else {			
		
		}
	}
	
	$("#menu-settings-overlay .setting-item").each(function(index){
		if(!$(this).hasClass("inactive")) {
			parentObject.menu_settings_visible = 1;
		}
	});
	if(!menu_settings_visible) {
		$("#menu-settings-trigger").hide();
	}
	$("#sub-menu-settings-overlay .setting-item").each(function(index){
		if(!$(this).hasClass("inactive")) {
			parentObject.sub_menu_settings_visible = 1;
		}
	});
	if(!sub_menu_settings_visible) {
		$("#sub-menu-settings-trigger").hide();
	}
	
	if(!menu_settings_visible && !sub_menu_settings_visible) {
		$("#menu-settings.panel").hide();
	}

	
}

function settingsFunctionality() 
{

	/* Settings Overlays */
	$("#menu-settings-trigger").click(function(event){
		event.preventDefault();
		if(!$("#sub-menu-settings-overlay").is(":visible")){
			$("#menu-settings-overlay").show();
			var settings = grabCurrentSettings($("#menu-settings-overlay form"));
			cancelBehavior(settings, $("#menu-settings-overlay"));
		}
	});
	$("#sub-menu-settings-trigger").click(function(event){
		event.preventDefault();
		if(!$("#menu-settings-overlay").is(":visible")){
			$("#sub-menu-settings-overlay").show();		 	
			var settings = grabCurrentSettings($("#sub-menu-settings-overlay form"));
			cancelBehavior(settings, $("#sub-menu-settings-overlay"));
		}		 
	});

	 $("a.cancel").click(function(event){
		 event.preventDefault();
		 $(".settings-overlay").hide();		 
	 });
	 
	 
	 $(".settings-overlay form").submit(function(event){
		 $(".settings-overlay").hide();
		 	previewMenu();
		 return false;
	 });
	 
	 $(".settings-overlay form a.submit").click(function(event){
		 event.preventDefault();
		 $(this).closest("form").submit();
	 });
}

function grabCurrentSettings(form)
{
	this.settings = new Array();
	var item = this;

	form.find("input[type='text'], select").each(function(index){
		var name = $(this).attr("name");
		item.settings[name] = $(this).val();		
	});	
	return this.settings;
}

function cancelBehavior(settings, overlay)
{
	
	overlay.find("a.cancel").click(function(event)
	{
		event.preventDefault();		
		for (index in settings) {
			var input = overlay.find("input[name='" + index + "'], select[name='" + index + "']");
			input.val(settings[index]);
		}
		if($("input[name='menu_width']").val() == 'auto') {
			$("#menu_width_element select").val('auto');
			$("#menu_width_element input").hide();			
		} else {
			$("#menu_width_element select").val('pixels');			
			$("#menu_width_element input").show();
		}
		
		overlay.find(".setting-item.color-picker").each(function(index){
			var color = $(this).find("input[type='text']").val();		
			$(this).find(".trigger span").css('backgroundColor', color);		
		});
		
	});
}


/*****************/
/*   INitialize    */
/*****************/

function initialize(menuObject) {

	var url = "/builder/get-menu-json/" + mid;
	$.getJSON(url, function(data) {

			var menuObject = data;
			if(menuObject.structure) {
				$("#menu-struct .wrapper").html(menuObject.structure);
			}

			var sortableobj =
			$('ol.sortable').nestedSortable({
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: menu_depth,
				isTree: true,
				expandOnHover: 700,
				startCollapsed: false
			});
			$('.disclose').on('click', function() {
				$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
			})
			
			initSettings(previewMenu, menuObject); /* hide and show available settings */

			settingsFunctionality();
			sortableFunctionality(sortableobj);

			/* Set first LI in sortable as active */
	    var item = getItem($('ol.sortable > li:first-child'));
			$("ol.sortable li").removeClass('active');    
	    $('ol.sortable > li:first-child').addClass('active');		
			populateUpdateForm(item);		

	});

	
}


/*****************/
/*   DOCUMENT    */
/*****************/


$(document).ready(function()
{   
		initialize();
		resizeView($(this));
				
	 /* Disable clicking inside menu preview */
   $('.preview-container a').not('.menu-image').click(function(event){ 
      event.preventDefault();
      return false;
   });
         
   // Update Menu Item
   $('#update-item-form').submit(function(event){
      event.preventDefault();
      updateItem($(this));
			previewMenu();
      return false;
   });
   $('#update-item-form input').blur(function(event){
      event.preventDefault();
			var form = $(this).closest('form');
      updateItem(form);
			previewMenu();
      return false;
   });   
   $('#update-item-form input[type="checkbox"]').change(function(event){
      event.preventDefault();
			var form = $(this).closest('form');
      updateItem(form);
			previewMenu();
      return false;
   });   
	 
 
   // Add Menu Item
   $('#add-menu-item').submit(function(event){
		  event.preventDefault();
      var title = $(this).children("#add-menu-item-name");
      var link = "";
      var target = 0;
      var active = 0;
			var maxNum = 0;

			$("ol.sortable li").each(function(index){
				var id = $(this).attr("id").match(/\d+/i);
				if(parseInt(id[0]) > maxNum) {
					maxNum = parseInt(id[0]);
				}
			});
			maxNum++;
      var html = '<li id="item_' + maxNum + '" data-title="'+title.val()+'" data-link="'+link+'" data-target='+target+' data-active='+active+'><div><span class="disclose"><span></span></span>' + title.val() + '<a class="remove" href="#">X</a></div></li>'; 

			if(title.val()) {
	      $("ol.sortable").append(html).each(function() {
	         sortableFunctionality();
	      });
	      previewMenu();
				title.val("");	
			}
      return false;
   });
 
   
   $('#toArray').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.preview-array").show();
			$("#sponsor").hide();      
      return false;
   });
   $('#toHtml').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.preview-html").show();
			$("#sponsor").hide();
      return false;
   });
   $('#toCSS').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.preview-css").show();
			$("#sponsor").hide();
      return false;
   });
   $('#toImages').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.preview-images").show();
			$("#sponsor").hide();
      return false;
   });
   $('#toJquery').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.preview-jquery").show();
			$("#sponsor").hide();
      return false;
   });

   $('#saveCSS').click(function(event){
      $("#preview-switcher input[type='submit']").removeClass('active');
      $(this).addClass('active');  
      $("#menu-builder .preview-container > div").hide();
      $("#menu-builder .preview-container > div.save-css").show();
			$("#sponsor").hide();
      return false;
   });
  $('#preview').click(function(){
    $("#preview-switcher input[type='submit']").removeClass('active');
    $(this).addClass('active');  
    $("#menu-builder .preview-container > div").hide();
    $("#menu-builder .preview-container > div.preview-menu").show();      
		$("#sponsor").show();
    return false;      
  });
  
  $(".help-trigger").fancybox({
    modal: false
  });  


  $('#save-menu-overlay-trigger').click(function(event){
    event.preventDefault();
    saveMenu(0);
    if(premium_account == 0) {
      $.fancybox({
        href: '#premium-overlay', 
        modal: false
      });
    } else {
      $.fancybox({
        href: '#save-overlay', 
        modal: false
      });
    }
    return false;
  });

  /* Ajax call to save menu to DB */
  $('#save-menu-form').submit(function(event)
	{ 
    var title = $("#save-menu-form input[name='menu_save_name']").val();
    $("h1.title").html(title);
    
    if(!title){
      alert("Please give your menu a name before saving");
      return false;
    }    
    saveMenu(1);
    $.fancybox.close();
    return false;
  });
  
    
   // Download menu
   $('#download-menu').click(function(event)
	 {
      event.preventDefault();
						
			var parser = new(less.Parser);	
		 	var currentTags = findCSSTags(menu_css);
		 	var menuSettings = Array();
		 	var jquerySettings = Array(); 
			

			for(tag in currentTags) {
				menuSettings[tag] = $("input[name='" + tag + "'], select[name='" + tag + "']").val();
				menuSettings[tag] = addUnits(menuSettings[tag], tag);
			}
			menuSettings['menuClass'] = "#cssmenu";
			menuSettings['includePath'] = "images/";
			if (menuSettings['menu_align'] == 'right' || menuSettings['menu_align'] == 'center') {
				var menu_class = 'align-' + menuSettings['menu_align'];
			} else {
				var menu_class = '';
			}
			
      var arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
      var html = renderTree(arraied,{'menu_id'  : 'cssmenu', 'menu_class' : menu_class});
      var css = renderCSS(menu_css, menuSettings);
      
      /* jQuery */
      jquerySettings['menuClass'] = "#cssmenu";
      var jquery = renderJquery(raw_jquery, jquerySettings);
			
		 	parser.parse(css, function (err, tree) {
		 	    if (err) { return console.error(err) }
					css = tree.toCSS();
		 	});
			
			menuSettings['includePath'] = "menu_source/images/";
      example_css = renderCSS(menu_css, menuSettings);

		 	parser.parse(example_css, function (err, tree) {
		 	    if (err) { return console.error(err) }
					example_css = tree.toCSS();
		 	});
      
      $.post( 
         "/builder/create-zip", //?menu_id="+menu_id+"&css="+css+"&html="+html,
         {language: "php", version: 5, html: html, css: css, example_css: example_css, menu_id: menu_id, jquery: jquery},  
         function(responseText){  
            var ifrm = document.getElementById("download-iframe");
            ifrm.src = root_path + "/builder/download?path="+responseText;
						console.log(responseText);
         },
         "html"  
      );  
   });
	 
	 $("#save-menu-css").click(function(event){
		 event.preventDefault();

		 editedCSS = $(".preview-container .save-css textarea").val();
		 menuId = menu_id;
		 
		 
     $.post( 
        "/builder/save-menu-css", //?menu_id="+menu_id+"&css="+css+"&html="+html,
        {language: "php", version: 5, editedCSS: editedCSS, menuId : menuId},  
        function(responseText){  
					console.log(responseText);
        },
        "html"  
     );
		
	 });
});


/***************/
/*  Resizing   */
/***************/

function resizeView (window)
{
  
	var builderHeight = window.height() - $("#header").outerHeight(true) - $("#toolbar").outerHeight(true);
	var rightContainerPadding = parseInt($("#menu-builder .right").css("padding-top")) + parseInt($("#menu-builder .right").css("padding-bottom"));
	
	var addHeight = $(".add-wrapper").outerHeight(true);
			
	$("#menu-builder .right").height(builderHeight - rightContainerPadding + "px");
	$("#menu-builder .right > div").height(builderHeight - rightContainerPadding + "px");		

	$("#menu-struct").height(builderHeight - addHeight + "px");
	$("#menu-builder .left > div").height(builderHeight + "px");		


	if(builderHeight < 500) {
		$("#sponsor").hide();
	} else {
		$("#sponsor").show();
	}

	previewHeight = builderHeight - rightContainerPadding - 15 -
									$("#builder-settings-panel").outerHeight(true) -
									$(".preview-header").outerHeight(true) - 
									$("#builder-footer").outerHeight(true);
	
	
	$(".preview-container").height(previewHeight + "px");	
	$(".preview-container textarea").height($(".preview-container").height() - 25);

}

$(window).resize(function(){

	resizeView($(this));

})


})(jQuery);;
(function ($) {

$(document).ready(function() {

  // Expression to check for absolute internal links.
  var isInternal = new RegExp("^(https?):\/\/" + window.location.host, "i");

  // Attach onclick event to document only and catch clicks on all elements.
  $(document.body).click(function(event) {
    // Catch the closest surrounding link of a clicked element.
    $(event.target).closest("a,area").each(function() {

      var ga = Drupal.settings.googleanalytics;
      // Expression to check for special links like gotwo.module /go/* links.
      var isInternalSpecial = new RegExp("(\/go\/.*)$", "i");
      // Expression to check for download links.
      var isDownload = new RegExp("\\.(" + ga.trackDownloadExtensions + ")$", "i");

      // Is the clicked URL internal?
      if (isInternal.test(this.href)) {
        // Skip 'click' tracking, if custom tracking events are bound.
        if ($(this).is('.colorbox')) {
          // Do nothing here. The custom event will handle all tracking.
        }
        // Is download tracking activated and the file extension configured for download tracking?
        else if (ga.trackDownload && isDownload.test(this.href)) {
          // Download link clicked.
          var extension = isDownload.exec(this.href);
          _gaq.push(["_trackEvent", "Downloads", extension[1].toUpperCase(), this.href.replace(isInternal, '')]);
        }
        else if (isInternalSpecial.test(this.href)) {
          // Keep the internal URL for Google Analytics website overlay intact.
          _gaq.push(["_trackPageview", this.href.replace(isInternal, '')]);
        }
      }
      else {
        if (ga.trackMailto && $(this).is("a[href^='mailto:'],area[href^='mailto:']")) {
          // Mailto link clicked.
          _gaq.push(["_trackEvent", "Mails", "Click", this.href.substring(7)]);
        }
        else if (ga.trackOutbound && this.href.match(/^\w+:\/\//i)) {
          if (ga.trackDomainMode == 2 && isCrossDomain(this.hostname, ga.trackCrossDomains)) {
            // Top-level cross domain clicked. document.location is handled by _link internally.
            event.preventDefault();
            _gaq.push(["_link", this.href]);
          }
          else {
            // External link clicked.
            _gaq.push(["_trackEvent", "Outbound links", "Click", this.href]);
          }
        }
      }
    });
  });

  // Colorbox: This event triggers when the transition has completed and the
  // newly loaded content has been revealed.
  $(document).bind("cbox_complete", function() {
    var href = $.colorbox.element().attr("href");
    if (href) {
      _gaq.push(["_trackPageview", href.replace(isInternal, '')]);
    }
  });

});

/**
 * Check whether the hostname is part of the cross domains or not.
 *
 * @param string hostname
 *   The hostname of the clicked URL.
 * @param array crossDomains
 *   All cross domain hostnames as JS array.
 *
 * @return boolean
 */
function isCrossDomain(hostname, crossDomains) {
  /**
   * jQuery < 1.6.3 bug: $.inArray crushes IE6 and Chrome if second argument is
   * `null` or `undefined`, http://bugs.jquery.com/ticket/10076,
   * https://github.com/jquery/jquery/commit/a839af034db2bd934e4d4fa6758a3fed8de74174
   *
   * @todo: Remove/Refactor in D8
   */
  if (!crossDomains) {
    return false;
  }
  else {
    return $.inArray(hostname, crossDomains) > -1 ? true : false;
  }
}

})(jQuery);
;
