

var url;
var imgPath = "images/";
var root = "desk";
var blocksize = 64;
var activeColor = "#ffa0a0";
var selectColor = "#a0ffa0";
var counter;
var timeout = 0;
var active;

var syncTime = 5000; // sync desk every 5 seconds


//============================================================
// init events

function init(initName, initUrl) {
// load desk from api
	url = initUrl;
	
	api({ data: "cmd=getareas" },function(data) {

		$.each($(data).find("area"), function (i,v) {
			var name = $(v).attr("name");
			var full = $(v).attr("full");
			var status = $(v).attr("status");

			api({ data: "cmd=getdesk&area="+name, name: name, status: status },createDesk);
		});
	});
	
	$("#do").bind("click",setDebug);
	
	
// start sync interval
//	setInterval(sync,syncTime);

// start watchdog
//	setInterval(watchdog,syncTime*3.14);
}


//============================================================
// synchronice desk with controll
function sync() {
// start sync process
	setStatusDisplay("syncStatus",1);

	getStatus();
}


//============================================================
// sync watchdog
function watchdog() {
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
// get status from api
function getStatus() {
	api({ data: "cmd=getstatus" },syncStatus);
}


//============================================================
// get block ids from area
function getBlocks() {
	api({ data: "cmd=getblock", type: "block" },selectBlocks);
}


//============================================================
// get switch ids from area
function getSwitches() {
	api({ data: "cmd=getswitch", type: "switch" },selectBlocks);
}


//============================================================
// get signal ids from area
function getSignals() {
	api({ data: "cmd=getsignal", type: "signal" },selectBlocks);
}


//============================================================
// get route targets from area
function getRoutes(id) {
	api({ data: "cmd=getroute&start="+id, type: "block" },selectBlocks);
}


//============================================================
// get route targets from area
function getRoute(start,target) {
	api({ data: "cmd=getroute&start="+start+"&target="+target, type: "block" },selectRoute);
}


//============================================================
// select route

function selectRoute(data,options) {
	$.each($(data).find("route"), function() {
		var id = $(this).attr("id");

// select all blocks of route		
		$.each($(this).find("block").children(), function() {
			setStatus((this).nodeName,1);
		});

// set all signals of route		
		$.each($(this).find("signal").children(), function() {
			setSignal((this).nodeName,1);
		});
	});
}


//============================================================
// mark blocks matching id and type

function selectBlocks(data,options) {

	$.each($(data).find("data").children(), function() {
		var id = (this).nodeName;
		var status = $(this).attr("status");
		var signal = $(this).attr("signal");
		var start = $(this).attr("start");

		$(".block["+options.type+"_id='"+id+"']")
			.css("background",selectColor)
			.attr("select",options.type)
			.attr("start",start);
	});

}


//============================================================
// synchronice status with desk

function syncStatus(data,options) {

//TODO write status to desk


// sync completed
	setStatusDisplay("syncStatus",2);
}


//============================================================
// create the desk

function createDesk(data,options) {
// create rows

	var element;
	var id;
	var full = $(data).find("area").attr("full");
	var order = $(data).find("area").attr("order");
	var area = options.name;
	var height = $(data).find("area").children().length;
	var width = "";


// create desk container
// on order position
	$("#"+root+"_"+order)
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
				.attr("status_id",status_id)
				.attr("signal_id",signal_id)
				.attr("switch_id",switch_id);


//============================================================
// insert field images
			element = $(vx).find("field");
			$.each(element, function(fx,fv) {
				$("#"+id).append("<img src='"+imgPath+$(fv).text()+"' class='block'>");
			});


//============================================================
// insert buttons
			element = $(vx).find("button");
			$.each (element, function(fx,fv) {
				var color = $(fv).attr("color");
				var type = $(fv).attr("type");
				var px = $(fv).attr("px");
				var py = $(fv).attr("py");
				var signal_id = $(vx).attr("signal_id");

				$("#"+id).append("<img src='"+imgPath+"button-"+color+".png' class='button px"+px+" py"+py+"'>")
					.bind("click", { "id": id, "type": type, "start": signal_id }, setPushed );
			});


//============================================================
// insert lights
			element = $(vx).find("light");
			$.each (element, function(fx,fv) {
				var px = $(fv).attr("px");
				var py = $(fv).attr("py");

			// off light image
				$("#"+id).append("<img src='"+imgPath+"black.png' class='offlight px"+px+" py"+py+"'>");
			
			// create lights
				$.each ($(fv).children(), function(lx,lv) {
					var name = (lv).nodeName;
					var lightId = $(lv).attr("id");
					var color = $(lv).text();
					
					var status = "";

// set id for block-status or signal
					switch (name) {
						case "status":
							var status = "block_id='"+status_id+"'";
							break;
						case "signal":
							var status = "signal_id='"+signal_id+"'";
							break;
					}

					$("#"+id).append("<img "+status+" light_id='"+name+lightId+"' src='"+imgPath+color+".png' class='light off px"+px+" py"+py+"'>");
				});
			});


//============================================================
// insert text
			element = $(vx).find("text");
			$.each (element, function(fx,fv) {
				var text = $(fv).text();

// text parameter => get attribute text
				if (text[0] == "$") {
					text = $(fv).parent().attr(text.substring(1));
				}

				var py = $(fv).attr("py");
				var cls = $(fv).attr("class");

				$("#"+id).append("<div class='text center py"+py+" "+cls+"'>"+text+"</div>");
			});
		});
	});
	$("#"+root).append("<div style='clear:both'/>");


// activate status/signal/switch 0 - position 0
	$("[light_id='signal0']").removeClass("off");
	$("[light_id='status0-1']").removeClass("off");


//TODO set status
//	setStatus("w830",1);
//	setStatus("s03",1);

//	setSignal("V830",1);

// set desk status to ok 
	setStatusDisplay("deskStatus",2);

// sync status with api
	syncStatus();
}


//============================================================
// button pushed
function setPushed(element, options) {
	var type = element.data.type;
	var id = element.data.id;
	var start = element.data.start;

	if (!$("#"+id).attr("active")) {

		switch (type) {
// WGT
			case "wgt":
			// => get list of switches from api
				getSwitches(id);
				break;

// WT
			case "wt":
				break;
		
// SGT
			case "sgt":
				getSignals(id);
				break;

// ST
			case "st":
				getRoutes(start);
				break;

// HAT
			case "hat":
				$(".light[block_id]").addClass("off");
				$("[light_id='signal1']").addClass("off");
				$("[light_id='signal0']").removeClass("off");
				break;

// GT
			case "gt":
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
		var start = $("#"+active).attr("start");


// get route
//TODO send action to api
		var route = getRoute(start,type_id);

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
		.removeAttr("start")
		.css("background-color","");

	$("#id-input").val("");
}


//============================================================
// set status lights
function setStatus(id,status) {
	$("[block_id='"+id+"'].light").addClass("off");
	$("[light_id='status"+status+"'][block_id='"+id+"'].light").removeClass("off");
}


//============================================================
// set signal lights
function setSignal(id,status) {
	$("[signal_id='"+id+"'].light").addClass("off");
	$("[light_id='signal"+status+"'][signal_id='"+id+"'].light").removeClass("off");
}


//============================================================
function setStatusDisplay(id, status) {
	var color = new Array("lightred","yellow","lightgreen");
	
	$("#"+id).css("background-color",color[status]);
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


