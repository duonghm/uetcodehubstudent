var templateText;

/** Setup editor
 */
function setupPrepairEditor() {
	$('#language').change(function () {
		var lang = $(this).val();
		var editor = ace.edit("editor");
		switch (lang) {
			case "Java":
				editor.getSession().setMode("ace/mode/java");
				break;
			case "C++":
				editor.getSession().setMode("ace/mode/c_cpp");
				break;
			default:
				editor.getSession().setMode("ace/mode/c_cpp");
		}
	});
}

/** Setup block code
 * @param {String} templateCode					Template Code
 */
function setupTemplateCode(templateCode) {
	templateText = templateCode;
	String.prototype.replaceAll = function(search, replacement) {
		var target = this;
		return target.replace(new RegExp(search, 'g'), replacement);
	};
	var INSERT_MARK = "//insert";
	
	// ACE Editor setting
	var editor = ace.edit("editor");
	var textarea = $('#source_code');
	editor.setTheme("ace/theme/chrome");
	editor.getSession().setMode("ace/mode/c_cpp");
	editor.getSession().on('change', function () {
		textarea.val(editor.getSession().getValue());
	});
	
	if(templateText.indexOf(INSERT_MARK) !== 1){
		editor.getSession().setValue(templateText.replaceAll(INSERT_MARK, ''));
	}else{
		editor.getSession().setValue(templateText);
	}
	
	
	textarea.val(editor.getSession().getValue());
	document.getElementById("editor").style.width = "100%"
	document.getElementById("editor").style.height = "400px";
	
	// Readonly code setting-----------------------
	
	var session = editor.getSession();
	Range = ace.require("ace/range").Range;
	
	var blockRanges = getReadonlyCode(templateText);
	for (var i = 0; i < blockRanges.length; i++) {
		var range = blockRanges[i];
		var markerId = session.addMarker(range, "readonly-highlight");
		range.start = session.doc.createAnchor(range.start);
		range.end = session.doc.createAnchor(range.end);
		range.end.$insertRight = true;
	}
	
	editor.keyBinding.addKeyboardHandler({
		handleKeyboard: function (data, hash, keyString, keyCode, event) {
			if ((keyCode <= 40 && keyCode >= 37)) return false;
	
			if (intersects(blockRanges)) {
				return {command: "null", passEvent: false};
			}
		}
	});
	
	before(editor, 'onPaste', preventReadonly);
	before(editor, 'onCut', preventReadonly);
	
	function before(obj, method, wrapper) {
		var orig = obj[method];
		obj[method] = function () {
			var args = Array.prototype.slice.call(arguments);
			return wrapper.apply(this, function () {
				return orig.apply(obj, orig);
			}, args);
		}
	
		return obj[method];
	}
	
	function intersects(blockRanges) {
		for (var i = 0; i < blockRanges.length; i++) {
			if (editor.getSelectionRange().intersects(blockRanges[i])) {
				return true;
			}
		}
		return false;
	}
	
	function preventReadonly(next) {
		if (intersects(blockRanges)) return;
		next();
	}
	
	function getReadonlyCode(templateText){
		var blocks = [];
		if(templateText != ""){
			var lines = templateText.split('\n');
			for(var i=0; i<lines.length; i++){
				if(lines[i].indexOf(INSERT_MARK) == -1){
					blocks.push(new Range(i,0,i+1,0));
				}
			}
		}
		return blocks;
	}
}