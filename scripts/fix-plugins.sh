#!/bin/sh

# php code-sniffer foxer
./wordpress/wpcs/vendor/bin/phpcbf -psvn --standard=tests/codesniffer.ruleset.xml plugins

