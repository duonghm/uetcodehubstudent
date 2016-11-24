function setBackButtonLocation(url) {
	var prevPage = getParameterByName("page");
	$('#backbtn').click(function(){
		if (prevPage == null) {
			document.location = url;
		} else {
			document.location =	url+"?page="+prevPage;
		}
	});
}

function getParameterByName(name) {
	url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}