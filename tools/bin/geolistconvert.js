#!/usr/bin/env node

// command-line tool for geolist conversion
var geolist2daoplayer = require('../lib/geolist2daoplayer');

var stdin = process.stdin,
	stdout = process.stdout,
	inputChunks = [];

stdin.resume();
stdin.setEncoding('utf8');

stdin.on('data', function (chunk) {
	inputChunks.push(chunk);
});

stdin.on('end', function () {
	var inputJSON = inputChunks.join(),
	    parsedData,
	    outputJson;
	try {
		parsedData = JSON.parse(inputJSON);
	} catch (err) {
		console.error('Error parsing input: '+err.message);
		process.exit(-1);
	}
	try {
		var output = geolist2daoplayer(parsedData);
		outputJSON = JSON.stringify(output, true, '    ');
	} catch (err) {
		console.error('Error converting input: '+err.message);
		process.exit(-1);
	}
	stdout.write(outputJSON);
	stdout.write('\n');
});
