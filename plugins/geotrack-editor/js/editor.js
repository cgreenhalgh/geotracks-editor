/** Editor Angular app for geolist metabox.
 *
 * Chris Greenhalgh, Copyright (c) The University of Nottingham, 2016
 * Requires Angular 1.5.
 *
 * @package gted
 */

var gtedApp = angular.module( 'gted', [] );

gtedApp.controller('test', ['$scope', '$http', function($scope,$http) {
	$scope.test = 'Hello Wordpress';
	console.log( 'Hello from gted' );
}]);
