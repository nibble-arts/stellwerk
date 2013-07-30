// set buttons
var url = "http://localhost/stellwerk/api.php";
var root = "desk";
var blocksize = 64;


// init events
function init() {
// load desk from api
	$.ajax(
	{
		url: url,
		type: "GET",
		dataType: "xml",
		async: true,
		cache: false,
		data: "cmd=getdesk&area=bgh",
		success: function (data) {
			createDesk(data);
		},
		error: function (xhr, msg) {
			alert("error: " + xhr.responseText + " " + msg);
		}
	});


	$("#do").bind("click", setDemo);
}


// create the desk
function createDesk(data) {
// create rows
	$.each ($(data).find("desk").children(), function(iy,vy) {

// create blocks
		$.each ($(data).find(vy).children(), function(ix,vx) {
console.log(vx);
			$("#"+root).append("<div id=''class='block' style='left: "+parseInt(ix*blocksize)+";top: "+parseInt(iy*blocksize)+";'>");

// combine images
			$.each ($(vx).find("field"), function(fx,fv) {
// img name				console.log($(fv).text());
			});
		});
	}
	);
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

