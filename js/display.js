// set buttons
var url = "http://localhost/stellwerk/api.php";
var imgPath = "images/";
var root = "desk";
var blocksize = 64;
var activeColor = "lightblue";
var counter;
var timeout = 3000;


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
	var element;
	var id;
	
// create blocks
	$.each ($(data).find("desk").children(), function(iy,vy) {
		$.each ($(data).find(vy).children(), function(ix,vx) {
			id = (vx).nodeName;

// get background color for block
			bg_color = $(vx).find("background").text();
			if (!bg_color) bg_color = "desk-color";

// create frame
			$("#"+root).append("<div id='"+id+"'class='block "+bg_color+"' style='left: "+parseInt(ix*blocksize)+";top: "+parseInt(iy*blocksize)+";'>");

// insert field images
			element = $(vx).find("field");
			$.each(element, function(fx,fv) {
				$("#"+id).append("<img src='"+imgPath+$(fv).text()+"' class='block'>");
			});

// insert buttons
			element = $(vx).find("button");
			$.each (element, function(fx,fv) {
				var color = $(fv).attr("color");
				var px = $(fv).attr("px");
				var py = $(fv).attr("py");

				$("#"+id).append("<img src='"+imgPath+"button-"+color+".png' class='button px"+px+" py"+py+"'>")
					.bind("click", { "id": id, "color": color }, setActive );
			});

// insert text
			element = $(vx).find("text");
			$.each (element, function(fx,fv) {
				var text = $(fv).text();
				var py = $(fv).attr("py");

				$("#"+id).append("<div class='text py"+py+"'>"+text+"</div>");
			});

		});
	}
	);
}


// set active block
function setActive(element, options)
{
	active = element.data.id;

	if ($("#"+active).css("active") != "") {
// stop timeout
		$(".block").css("active","");
	}
	else {
// set block active
		$(".block").css("active","");
		$(".block").css("background-color","");
		$("#"+active).css("active",activeColor);
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
	$(".block").css("active","");
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

