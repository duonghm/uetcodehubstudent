function animateView(element, delay, animClass) {
	setTimeout(function() {
		element.addClass(animClass+' animated');
		element.css({"opacity": "1"});
	}, delay);
}

function showAnimation(elemClass, animClass) {
	var del = 0;
	$(elemClass).each( function() {
		del += 100;
		$(this).css({"opacity": "0"});
		animateView($(this), del, animClass);
	});
}