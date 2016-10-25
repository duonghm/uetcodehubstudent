<span id="countDownTimer"></span>
<script>
	var remainingTime = {{$remainTime}};
	var countDownElem = document.getElementById('countDownTimer');
	var minutes, seconds;
	
	function timeTick() {
		minutes = Math.floor(remainingTime/60);
		seconds = remainingTime%60;
		if (remainingTime > 300) {
			countDownElem.style.color = 'cornflowerblue';
		} else if (remainingTime > 0) {
			countDownElem.style.color = '#df8505';
		} else {
			countDownElem.style.color = '#e7505a';
		}
		
		if (minutes > 0)
			countDownElem.innerHTML = "Remaining time: " + minutes + "m " + seconds + "s";
		else if (minutes == 0)
			countDownElem.innerHTML = "Remaining time: " + seconds + "s";
		else {
			countDownElem.innerHTML = "TIME IS UP";
			if (seconds == 0)
				toastr.success("Congratulation!", "You've finished your test.");
			clearInterval(mTimer);
		}
		remainingTime--;
	}
	
	var mTimer = setInterval(timeTick, 1000);
</script>


