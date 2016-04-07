#!/bin/sh

# copy plugins to test wp installation
WPDIR=$(pwd)/wordpress
(cd plugins; tar zcf - */) | (cd /srv/wordpress-dev/wordpress/wp-content/plugins; tar zxf -)
tar zcf /tmp/tools.tgz tools/bin/ tools/lib/ tools/package.json  tools/README.md tools/src/ tools/test/
(cd /srv/wordpress-dev/wordpress/wp-content/plugins/geotrack-editor; npm install /tmp/tools.tgz)
(cd /srv/wordpress-dev/wordpress && ${WPDIR}/wp-cli.phar plugin activate geotrack-editor)

