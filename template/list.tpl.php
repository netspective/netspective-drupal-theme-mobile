<script type="text/javascript">

connect("<?php echo $settings['host'];?>","<?php echo $settings['port'];?>","device");	
setInterval("dlist_send()",10000);

function checkdata(device_list) {
	$("div.action_text").html('');

	for(var i=0; i<device_list.length; i++) {
		if(device_list[i] == '') { continue; }
		if($("#"+device_list[i]).get(0)){
			var domainname = $('#'+device_list[i]+'_value').val();
			$('#'+ device_list[i]).html("<a href='<?php print $base_url;?>/agent/present/remote-view-real-time/"+domainname+"/"+device_list[i]+"'>Started</a>");
		}
	}
}

var msgsize;
	var ws;
	var url;
	var avgbytes;
	var device_dynamic;
	var enabled_button =  new Array();
	var device_list =  new Array();
				
function connect(host_url, port, type) {
    url = "ws://"+host_url+":"+port+"/"+type;
	console.log(url);
	if ("WebSocket" in window) {
		ws = new WebSocket(url);
	} else if ("MozWebSocket" in window) {
		ws = new MozWebSocket(url);
	} else {
		chat_message("This Browser does not support WebSockets");
		return;
	}
	ws.onopen = function(e) {
		chat_message("A connection to "+url+" has been opened.");

		var cookie_val = readCookie('cookie_value');
		if(!cookie_val) { createCookie('cookie_value','set',1); }
		else { disconnect(); connect(host_url, port, type); }
		null_send();
	};
	
	ws.onerror = function(e) {
		chat_message("An error occured, see console log for more details.");
		console.log(e);
	};
	
	ws.onclose = function(e) {
		chat_message("The connection to "+url+" was closed.");
	};
	
	ws.onmessage = function(e) {
		var message = JSON.parse(e.data); 
		
		if (message.type == "msg") {
				chat_message2(message.value,message.sender);
		} else if (message.type == "participants") {}
	};
}

function chat_message(message) {
	console.log(message);
}

function chat_message2(message,sender) 
{

		if((message=="DLIST") || (message == "dynamiclist")) { return; }
var devices = message;

var device_dynamic = "";
	var d1 = devices.split(';');
var d2 = new Array();
	for(var i=0; i<d1.length; i++) {

device_dynamic += d1[i]+",";
	}
//alert(device_dynamic);	
device_list = device_dynamic.split(',');

	checkdata(device_list);

	if (arguments.length == 1) 
	{
		sender = "";
	}
	
	var style;
	if (sender == "") {
		style = "client";
	} else if (sender == "server") {
		style = "server";
		sender = "["+sender+"]";
	} else {
		style = "message";
		sender = "["+sender+"]";
	}
	if(message == "connected") { chat_message(message); }
	
}

function disconnect() {
	eraseCookie('cookie_value');
	
	ws.close();
	chat_message("Closed");
}


function toggle_connect() {
		connect();
}

function send(v) {
	if (ws === undefined || ws.readyState != 1) {
		chat_message("Websocket is not avaliable for writing");
		return;
	}
	var devices_sel=v;

	ws.send("START:"+devices_sel);
}

function send_stop(v) {
	if (ws === undefined || ws.readyState != 1) {
		chat_message("Websocket is not avaliable for writing");
		return;
	}
	var devices_sel=v;
	ws.send("STOP:"+devices_sel);
}

function null_send() {
	if (ws === undefined || ws.readyState != 1) {
		chat_message("Websocket is not avaliable for writing");
		return;
	}
	ws.send("dynamiclist");
}

function dlist_send() {
        if (ws === undefined || ws.readyState != 1) {
                chat_message("Websocket is not avaliable for writing");
                return;
        }
        ws.send("DLIST");
}
</script>
	<div data-role="content">
		<div class="content-primary">
			<div class="ui-grid-b">
				<?php foreach ($devices as $key => $device): ?>
					<div class="ui-block-a"><div class="ui-bar ui-bar-f"><?php echo $device['name_text']; ?></div></div>
					<div class="ui-block-b"><div class="ui-bar ui-bar-f"><?php echo $device['sub_text']; ?></div></div>
					<div class="ui-block-c"><div class="ui-bar ui-bar-f" id="<?php echo $device['sub_text']; ?>"><div id="<?php echo $device['sub_text']; ?>" class="action_text"></div></div></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
