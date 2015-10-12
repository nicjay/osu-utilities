
var tmpValue = new Array;
var ignoreUnload=false;
var forceWarning = false;


function confirmUnload() {
	if ((forceWarning==true && ignoreUnload==false) || (formChanged('edit-form') == true && ignoreUnload==false)) return 'You have not saved your data.';
    forceWarning = false;
}

function formChanged(fm) {
	if (!fm) fm = 'edit-form';
	var dr = $('#edit-form')
	if (dr == null) return;

	var t = $('#edit-form input');
	var s = $('#edit-form select');
	var ta = $('#edit-form textarea');
	var tmp;
	
	for(var i=0;i < t.length;i++) {
		tmp = t[i].value;
		if (t[i].type == 'checkbox' || t[i].type == 'radio') tmp = t[i].checked;
		if (tmpValue[t[i].id] != tmp && t[i].type != 'submit'&& t[i].type != 'hidden') { 
//			alert(tmpValue[t[i].id] + ' != ' +tmp + ' : type = ' + t[i].type + ' : name = ' + t[i].name); 
			return true;
		}
	}
	for(i=0;i < s.length;i++) if (tmpValue[s[i].id] != s[i].value) return true;
	for(i=0;i < ta.length;i++) {
        if (tinyMCE.getInstanceById(ta[i].id) != null){
            if (tinyMCE.getInstanceById(ta[i].id).isDirty() == true) {
                return true;
            }
        }
		else if (tmpValue[ta[i].id] != ta[i].value) { 
//			alert(tmpValue[ta[i].id] + ' != ' +ta[i].value); 
			return true;
		}
	}
}

function initValues(fm) {
	if (!fm) fm = 'edit-form';
	var dr = document.getElementById('edit-form');
	if (dr == null) return;
	
	var t = $('#edit-form input');
	var s = $('#edit-form select');
	var ta = $('#edit-form textarea');
	var tmp;

	for(var i=0;i < t.length;i++) {
		tmp = t[i].value;
		if (t[i].type == 'checkbox' || t[i].type == 'radio') tmp = t[i].checked;
		if (!t[i].id) t[i].id = 'autoId_input_' + i;
		if (t[i].type !='submit' && t[i].type != 'hidden') {
            tmpValue[t[i].id] = tmp;
        }
	}
	for(i=0;i < s.length;i++) {
		if (!s[i].id) s[i].id = 'autoId_select_' + i;
		tmpValue[s[i].id] = s[i].value;
	}
	for(i=0;i < ta.length;i++) {
		if (!ta[i].id) ta[i].id = 'autoId_texarea_' + i;
		tmpValue[ta[i].id] = ta[i].value;
	}
    
}
