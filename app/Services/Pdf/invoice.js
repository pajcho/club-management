// PhantomJS Screenshot Capture...
var fs = require('fs'),
    args = require('system').args,
    page = require('webpage').create();

var render_time = 3000;
var time_out = 9000;

window.setTimeout(function () {
    console.log("Shit's being weird no result within: " + time_out + "ms");
    phantom.exit(1);
}, time_out);

// Read the HTML view into the page...
page.content = fs.read(args[1]);
page.viewportSize = {width: 750, height: 600};
page.paperSize = {format: 'A4', orientation: 'portrait', margin: '5mm'};

// Give the page some time to render...
window.setTimeout(function() {
	page.render(args[1]);
	phantom.exit();
}, render_time);