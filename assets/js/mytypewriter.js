// Typewriter animation

window.addEventListener("load", typewriter);

function typewriter() {
	var elem = document.querySelector(".page .wrap>#primary .entry-title");
	
	if (elem === null) return;
	//elem = elem[0];
	innerContent = elem.innerHTML;
	var myInt = setInterval(myTimer, 80);
	var count = 0;
	/*requestAnimationFrame(myTimer);*/
	

	function myTimer() {
		count++;
		elem.innerHTML = '<span class="showtxt">' + innerContent.substring(0, count - 1) + '</span><span class="hidetxt">' + innerContent.substring(count - 1) + '</span>';
		if (count == 1) {elem.style.visibility='visible';}
		if (count == elem.innerHTML.length) { /*cancelAnimationFrame;*/ clearInterval(myInt); }
		/*requestAnimationFrame(myTimer);*/
	}
}