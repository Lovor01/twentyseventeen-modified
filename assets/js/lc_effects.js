appear({
	
	// namjesti što se prati
	elements: function elements(){
    // work with all elements with the class "track"
    return document.getElementsByClassName('magic');
	},
	appear: function appear(el){
		el.className="magic1";
  }
	
});

/*appear({
	
	// namjesti što se prati
	elements: function elements(){
    // work with all elements with the class "track"
    return document.getElementsByClassName('ribbon');
	},
	appear: function appear(el){
		lc_start_pos = window.scrollY;;
  }
	
});*/

// Reference: http://www.html5rocks.com/en/tutorials/speed/animations/

var lc_last_known_scroll_position = 0;
var lc_ticking = false;
var lc_start_pos = 0;

function doSomething(scroll_pos) {
  // do something with the scroll position
  var ggg = document.getElementsByClassName('ribbon');
  //ggg[0].style.background='linear-gradient(to right, rgb(255,' + (ggg[0].offsetTop - scroll_pos) / Math.max(document.documentElement.clientHeight, window.innerHeight || 0) * 255 +', 0), rgb(0,255,0))';
  var rect = ggg[0].getBoundingClientRect();
//	console.log(rect.top, scroll_pos);
  // console.log(scroll_pos);
}

window.addEventListener('scroll', function(e) {

  lc_last_known_scroll_position = window.scrollY;
  // pageYOffset umjesto scrollY za bolju kompatibilnost

  if (!lc_ticking) {

    window.requestAnimationFrame(function() {
      doSomething(lc_last_known_scroll_position);
      lc_ticking = false;
    });
     
    lc_ticking = true;

  }
  
});