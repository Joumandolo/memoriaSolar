<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ejemplos Solar</title>
    <link href="../flot/flot-flot-1a99246/examples/layout.css" rel="stylesheet" type="text/css">
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="../flot/flot-flot-1a99246/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/flot-flot-1a99246/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/flot-flot-1a99246/jquery.flot.navigate.js"></script>
    <script language="javascript" type="text/javascript" src="../solar/dateFormat.js"></script>
 
<style type="text/css">
    #placeholder .button {
        position: absolute;
        cursor: pointer;
    }
    #placeholder div.button {
        font-size: smaller;
        color: #999;
        background-color: #eee;
        padding: 2px;
    }
    .message {
        padding-left: 50px;
        font-size: smaller;
    }
    </style>

</head>
<body>
	<div id="placeholder" style="width:500px;height:300px;"></div>
	<div id="leg" style="width:100px;height:200px;right:320px;top:550px;position:absolute;font-size:smaller;pading:0px;border:0px;margin:0px"></div>
	<br><p id="choices">Show:</p>

<script type="text/javascript">
$.ajax({
	url: '../solar/a.php',
	type: 'GET',
	dataType: 'json',
	async: false,
	success: function(data, textStatus, jqXHR){
	datosJson = data;
	},
});

var placeholder = $("#placeholder");
var options = {
	series: {
		lines: { show: true, shadowSize: 0 },
		//points: { show: true }
	},
	grid: {
		hoverable: true, 
		//clickable: true,
		axixMargin: 5
	},
	zoom: { interactive: true},
	pan: { interactive: true},
	xaxis: {
		//min: -1.2,
		//max: 1.2 }
		monthName: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		mode: "time",
		ticks: 9,
		timeformat: "%d\r%b\r%Hhrs",
		TickSize: [1, "hour"],
		zoomRange: [10000000000, 1000000000000],
                panRange: [1300000000000, 1340000000000]
	},
	yaxis: {
		zoomRange: [1, 1000],
		panRange: [0, 1000]
	},
	legend: {
		show: true,
		//labelFormatter: "fn", //null or (fn: string, series object -> string)
		//labelBoxBorderColor: "white",
		noColumns: 1,
		position: "nw", //"sw" or "nw" or "se" or "sw"
		margin: 2, //number of pixels or [x margin, y margin]
		//backgroundColor: "red", //null or color
		//backgroundOpacity: "0.8", //number between 0 and 1
		container: $("#leg"),//null or jQuery object/DOM element/jQuery expression
	},
};

var plot = $.plot(placeholder, datosJson, options);

//configurcion de la etiqueta que muestra informacion sobre los puntos
function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css( {
		position: 'absolute',
		display: 'none',
		top: y + 5,
		left: x + 5,
		border: '1px solid #fdd',
		padding: '2px',
		'background-color': '#fee',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}

//habilitacion de las etiquetas que muetran informacion y llamar a la etiqueta sobre el grafico
var previousPoint = null;
placeholder.bind("plothover", function (event, pos, item) {
//$("#placeholder").bind("plothover", function (event, pos, item) {
	//if ($("#enablePosition:checked").length > 0) {
	//	var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
	//	$("#hoverdata").text(str);
	//}

	//if ($("#enableTooltip:checked").length > 0) {
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;       
				$("#tooltip").remove();
				var x = item.datapoint[0],
				y = item.datapoint[1].toFixed(2);
     				
				var fecha = new Date(x);
				fecha = fecha.format("d M Y, H:i:s");		
				var label = item.series.label.split(" ");
				showTooltip(item.pageX, item.pageY, fecha + "<br>" + y + " " + label[1]);
			}
		}
	else {
		$("#tooltip").remove();
		previousPoint = null;            
		}
	//}
});

//placeholder.bind("plotclick", function (event, pos, item) {
//$("#placeholder").bind("plotclick", function (event, pos, item) {
//	if (item) {
//		$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
//		plot.highlight(item.series, item.datapoint);
//		}
//});

//}); 
/*
//show pan/zoom messages to illustrate events 
placeholder.bind("plotpan", function (event, plot) {
//$("placeholder").bind("plotpan", function (event, plot) {
	var axes = plot.getAxes();
	$(".message").html("Panning to x: "  + axes.xaxis.min.toFixed(2)
		+ " &ndash; " + axes.xaxis.max.toFixed(2)
		+ " and y: " + axes.yaxis.min.toFixed(2)
		+ " &ndash; " + axes.yaxis.max.toFixed(2));
});

placeholder.bind("plotzoom", function (event, plot) {
//$("placeholder").bind("plotzoom", function (event, plot) {
	var axes = plot.getAxes();
	$(".message").html("Zooming to x: "  + axes.xaxis.min.toFixed(2)
		+ " &ndash; " + axes.xaxis.max.toFixed(2)
		+ " and y: " + axes.yaxis.min.toFixed(2)
		+ " &ndash; " + axes.yaxis.max.toFixed(2));
});
*/
//add zoom out button 
$('<div class="button" style="right:1085px;top:20px">zoom out</div>').appendTo(placeholder).click(function (e) {
	e.preventDefault();
	plot.zoomOut();
});

// and add panning buttons
// little helper for taking the repetitive work out of placing
// panning arrows
function addArrow(dir, right, top, offset) {
	$('<img class="button" src="arrow-' + dir + '.gif" style="right:' + right + 'px;top:' + top + 'px">').appendTo(placeholder).click(function (e) {
		e.preventDefault();
		plot.pan(offset);
	});
}

//addArrow('left', 1120, 60, { left: -100 });
//addArrow('right', 1090, 60, { left: 100 });
//addArrow('up', 1105, 45, { top: -100 });
//addArrow('down',1105, 75, { top: 100 });

//hard-code color indices to prevent them from shifting as
// countries are turned on/off
var i = 0;
$.each(datosJson, function(key, val) {
	val.color = i;
	++i;
});
    
// insert checkboxes 
var choiceContainer = $("#choices");
$.each(datosJson, function(key, val) {
	choiceContainer.append('<input type="checkbox" name="' + key +
		'" checked="checked" id="id' + key + '">' +
		'<label for="id' + key + '">'
		+ val.label + '</label>');
});
choiceContainer.find("input").click(plotAccordingToChoices);
    
function plotAccordingToChoices() {
	var data = [];

	choiceContainer.find("input:checked").each(function () {
		var key = $(this).attr("name");
		if (key && datosJson[key])
		data.push(datosJson[key]);
	});

	if (data.length > 0)
	plot = $.plot(placeholder, data, options);
	//$.plot($("#placeholder"), data, {
	//	yaxis: { min: 0 },
	//	xaxis: { tickDecimals: 0 }
	//});
}
plotAccordingToChoices();

//});  


</script>

 </body>
</html>
