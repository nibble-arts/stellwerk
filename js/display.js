// set buttons
var area = "bgh";

var url = "http://localhost/stellwerk/api.php";
var imgPath = "images/";
var root = "desk";
var blocksize = 64;
var activeColor = "#ffa0a0";
var selectColor = "#a0ffa0";
var counter;
var timeout = 0;
var active;


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
	
	$("#do").bind("click",setDebug);
}


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

		if	($(data).find("error").text() == "no error")
			callback($(data).find("apiXml"),options);
		else
			alert($(data).find("error").text());

		},
		error: function (xhr, msg) {
			alert("error: " + xhr.responseText + " " + msg);
		}
	});
}


//============================================================
// get block ids from area
function getBlocks(area) {
	api({ data: "cmd=getblock", type: "block" },selectBlocks);
}


//============================================================
// get switch ids from area
function getSwitches(area) {
	api({ data: "cmd=getswitch", type: "switch" },selectBlocks);
}


//============================================================
// get signal ids from area
function getSignals(area) {
	api({ data: "cmd=getsignal", type: "signal" },selectBlocks);
}


//============================================================
// mark blocks matching id and type

function selectBlocks(data,options) {

	$.each($(data).find("data").children(), function() {
		var id = (this).nodeName;
		var status = $(this).attr("status");
		var signal = $(this).attr("signal");

		$(".block["+options.type+"_id='"+id+"']")
			.css("background",selectColor)
			.attr("select",options.type);
	});

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

//============================================================
// create blocks
	$.each ($(data).find("area").children(), function(iy,vy) {
		$.each ($(data).find(vy).children(), function(ix,vx) {
			id = area+"_"+(vx).nodeName;

// get background color for block
			bg_color = $(vx).find("background").text();
			if (!bg_color) bg_color = "desk-color";

// create block
			$("#"+area).append("<div id='"+id+"' class='block "+bg_color+"' style='left: "+parseInt(ix*blocksize)+";top: "+parseInt(iy*blocksize)+";'>");
			
//============================================================
// insert reference ids
			var status_id = $(vx).attr("status_id");
			var block_id = $(vx).attr("block_id");
			var signal_id = $(vx).attr("signal_id");
			var switch_id = $(vx).attr("switch_id");

			$("#"+id).attr("status_id",status_id)
				.attr("block_id",block_id)
				.attr("signal_id",signal_id)
				.attr("switch_id",switch_id);


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
				var cls = $(fv).attr("class");

				$("#"+id).append("<div class='text center py"+py+" "+cls+"'>"+text+"</div>");
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

	if (!$("#"+id).attr("active")) {

		switch (type) {
// WGT
			case "wgt":
			// => get list of switches from api
				getSwitches(id);
				break;

// WT
			case "wt":
				console.log("WT");
				break;
		
// SGT
			case "sgt":
				getSignals(id);
				break;

// ST
			case "st":
				console.log("ST");
				break;

// HAT
			case "hat":
				getBlocks(id);
				break;

// GT
			case "gt":
				console.log("GT");
				break;
		
			default:
				console.log("unknow button type");
				break;
		}
	}

	setActive(id);
}


//============================================================
// set active block

function setActive(id)
{
	active = id;

// selected button => send action
	if ($("#"+active).attr("select")) {
		var type = $("#"+active).attr("select");
		var type_id = $("#"+active).attr(type+"_id");
		
		console.log($("#"+active));
		alert(type+" action on "+type_id);

		clearActive();
	}
	else {

// button pushed
		if ($("#"+active).attr("active")) {
		// stop timeout
			clearActive();
		}
		else {
		// set block active
			clearActive();
			active = id;
			$("#id-input").val(id);

			$("#"+active).attr("active",id)
				.css("background-color",activeColor);

		// start/restart timeout
			if (timeout) {
				clearInterval(counter);
				counter = setInterval (clearActive,timeout);
			}
		}
	}
}


// clear all active blocks
function clearActive() {
	clearInterval(counter);

	active = "";
	$(".block")
		.removeAttr("active")
		.removeAttr("select")
		.css("background-color","");

	$("#id-input").val("");
}


//============================================================
// set status
function setStatus(id,status) {
console.log("set "+id+" to status "+status);

}


//============================================================
// set signal
function setSignal(id,status) {
console.log("set "+id+" to signal "+status);
}


//============================================================
// set light
function setLight(id,status) {

}


//============================================================
//============================================================

// debug function
function setDebug() {
	var id = $("#id-input").val();
	var status = $("#status-input").val();
	var signal = $("#signal-input").val();
	
	setStatus(id,status);
	setSignal(id,signal);
}


