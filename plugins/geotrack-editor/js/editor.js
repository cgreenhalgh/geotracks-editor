/** Editor Angular app for geolist metabox.
 *
 * Chris Greenhalgh, Copyright (c) The University of Nottingham, 2016
 * Requires Angular 1.5.
 *
 * @package gted
 */

var gtedApp = angular.module( 'gted', [] );

gtedApp.controller('test', ['$scope', '$http', function($scope,$http) {
	$scope.test = jQuery( '#gted_tracks' ).val();
	// Remember gted_geolist_id.
	console.log( 'Hello from gted' );
	$scope.update = function() {
		jQuery( '#gted_tracks' ).val( $scope.test );
	};
	$scope.addingGeotrack = false;
	$scope.addGeotrack = function() {
		$scope.addingGeotrack = true;
		console.log('addGeotrack...');
	};
	$scope.closeModal = function() {
		$scope.addingGeotrack = false;
	};
	// Search geotrack.
	$scope.searching = false;
	$scope.searchText = '';
	$scope.searchChanged = function() {
	};
	$scope.searchKey = function( $event ) {
		if ( $event.which == 13 ) {
			$scope.search();
			$event.preventDefault();
		}
	};
	$scope.search = function() {
		console.log( 'search for '+$scope.searchText );
		$scope.searching = true;
	};
}]);
