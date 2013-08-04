// set buttons
var area = "bgh";

var url = "http://localhost/stellwerk/api.php";
var imgPath = "images/";
var root = "desk";
var blocksize = 64;
var activeColor = "#ffa0a0";
var counter;
var timeout = 3000;
var active;


//============================================================
// send request to api
// call callback function on success

function api(options,callback) {
	$.ajax(
	{
		url: url,
		type: "GET",
		dataType: "xml",
		async: true,
		cache: false,
		data: options.data,
		success: function (data) {
			callback(data,options);
		},
		error: function (xhr, msg) {
			alert("error: " + xhr.responseText + " " + msg);
		}
	});
}


//============================================================
// init events

function init() {
// load desk from api
	api({ data: "cmd=getareas" },function(data) {
		$.each($(data).find("area"), function (i,v) {
			var name = $(v).attr("name");
			var full = $(v).attr("full");
			var status = $(v).attr("status");

			api({ data: "cmd=getdesk&area="+name, name: name, status: status },createDesk);
		});
	});
}


//============================================================
// get switch ids from area
function getSwitches(area) {

//console.log(area);

//	api({ data: "cmd=getswitch&area="+area },showSwitches);
}


//============================================================
// get switch ids from area
function getAreas() {

	api({ data: "cmd=getareas" },showSwitches);
}


//============================================================
// create the desk

function createDesk(data,options) {
// create rows
	var element;
	var id;
	var full = $(data).find("area").attr("full");
	var area = options.name;
	var height = $(data).find("area").children().length;
	var width = "";


// create desk container
	$("#"+root)
		.append("<div class='title'>"+full+"</div>")
		.append("<div id='"+area+"' full='"+full+"' class='desk' style='height: "+parseInt(height*blocksize)+"'>");

// create blocks
	$.each ($(data).find("area").children(), function(iy,vy) {
		$.each ($(data).find(vy).children(), function(ix,vx) {
			id = area+"_"+(vx).nodeName;

// get background color for block
			bg_color = $(vx).find("background").text();
			if (!bg_color) bg_color = "desk-color";

// create block
			$("#"+area).append("<div id='"+id+"'class='block "+bg_color+"' style='left: "+parseInt(ix*blocksize)+";top: "+parseInt(iy*blocksize)+";'>");

// insert field images
			element = $(vx).find("field");
			$.each(element, function(fx,fv) {
				$("#"+id).append("<img src='"+imgPath+$(fv).text()+"' class='block'>");
			});

// insert buttons
			element = $(vx).find("button");
			$.each (element, function(fx,fv) {
				var color = $(fv).attr("color");
				var type = $(fv).attr("type");
				var px = $(fv).attr("px");
				var py = $(fv).attr("py");

				$("#"+id).append("<img src='"+imgPath+"button-"+color+".png' class='button px"+px+" py"+py+"'>")
					.bind("click", { "id": id, "type": type, }, setPushed );
			});

// insert text
			element = $(vx).find("text");
			$.each (element, function(fx,fv) {
				var text = $(fv).text();
				var py = $(fv).attr("py");

				$("#"+id).append("<div class='text center py"+py+"'>"+text+"</div>");
			});
		});
	});
	$("#"+root).append("<div style='clear:both'/>");
}


//============================================================
// button pushed
function setPushed(element, options) {
	var type = element.data.type;
	var id = element.data.id;

	switch (type) {
// WGT
		case "wgt":
		// => get list of switches from api
			console.log("WGT");
			getSwitches(id);
			break;

// WT
		case "wt":
			console.log("WT");
			break;
		
// SGT
		case "sgt":
			console.log("SGT");
			break;

// ST
		case "st":
			console.log("ST");
			break;

// HAT
		case "hat":
			console.log("HAT");
			break;

// GT
		case "gt":
			console.log("GT");
			break;
		
		default:
			console.log("unknow button type");
			break;
	}

	setActive(id);
}


//============================================================
// set selected blocks
function setSelected(ids) {

}


//============================================================
// set active block

function setActive(id)
{
	active = id;

	if ($("#"+active).attr("active")) {
// stop timeout
		clearActive();
	}
	else {
// set block active
		clearActive();
		active = id;

		$("#"+active).attr("active",activeColor)
			.css("background-color",activeColor);

// start/restart timeout
		if (timeout) {
			clearInterval(counter);
			counter = setInterval (clearActive,timeout);
		}
	}
}


// clear all active blocks
function clearActive() {
	clearInterval(counter);

	active = "";
	$(".block").removeAttr("active").css("background-color","");
}

