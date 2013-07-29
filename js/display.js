// set buttons
var imageSrc = "images/";
var active;
var activeColor = "#989DE4";
var counter;
var timeout = 0; // ms for auto timeout for selections (0 = no timeout)


// init events
function init() {
	$("#a1").bind("click", { id: "a1",color: "green" }, setActive);
	$("#a2").bind("click",{ id: "a2",color: "red" }, setActive);

	$("#do").bind("click", setDemo);
	
}


// set active block
function setActive(element, options)
{
	active = element.data.id;

	if ($("#"+active).css("background-color") != "transparent") {
// stop timeout
		$(".block").css("background-color","");
	}
	else {
// set block active
		$(".block").css("background-color","");
		$("#"+active).css("background-color",activeColor);
		$("#id-input").val(active);

// start/restart timeout
		if (timeout) {
			clearInterval(counter);
			counter = setInterval (clearActive,timeout);
		}
	}
}


// clear all active blocks
function clearActive() {
	active = "";
	$(".block").css("background-color","");
}


//TODO only for test
function setDemo()
{
	var color = $("#color-input").val();
	var x = $("#X-input").val();
	var y = $("#Y-input").val();

	setLight(active,color,x,y);
}


// set a light in a block
// block ... id of the block to alter
// color ... color to be displayed
//           clear if color is empty
// x,y ... coordinates from 1-3
function setLight(block,color,x,y) {
	var classes = "light px"+x+" py"+y;
	
	$("#_"+block+".px"+x+".py"+y).remove();

	if (color)
		$("#"+block).append("<img id='_"+block+"' class='"+classes+"' src='"+imageSrc+color+".png'>");
}

