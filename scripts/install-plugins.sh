#!/bin/sh

# copy plugins to test wp installation
WPDIR=$(pwd)/wordpress
(cd plugins; tar zcf - */) | (cd /srv/wordpress-dev/wordpress/wp-content/plugins; tar zxf -)
(cd /srv/wordpress-dev/wordpress && ${WPDIR}/wp-cli.phar plugin activate geotrack-editor)

