/** Editor Angular app for geolist metabox.
 *
 * Chris Greenhalgh, Copyright (c) The University of Nottingham, 2016
 * Requires Angular 1.5.
 *
 * @package gted
 */

var gtedApp = angular.module( 'gted', [] );

/* Service which provides access to localisation data from wordpress,
 * i.e. window.gted_wp, which includes ajax_url and nonce.
 */
gtedApp.factory('gted_wp', ['$window', function($window) {
	return $window.gted_wp;
}]);

gtedApp.controller('test', ['$scope', '$http', 'gted_wp', '$httpParamSerializer', function($scope,$http,gted_wp,$httpParamSerializer) {
	console.log( 'Hello from gted' );
	var gted_geolist = jQuery( '#gted_geolist' ).val();
	$scope.test = gted_geolist;
	try {
		$scope.geolist = JSON.parse( gted_geolist );
	} catch (err) {
		console.log( 'Error parsing geolist: ' + err.message + ' in ' + gted_geolist );
	}
	$scope.update = function() {
		console.log( 'direct set gted_geolist: ' + $scope.test );
		jQuery( '#gted_geolist' ).val( $scope.test );
	};
	// Remember gted_geolist_id.
	$scope.changed = function() {
		$scope.test = JSON.stringify( $scope.geolist );
		jQuery( '#gted_geolist' ).val( JSON.stringify( $scope.geolist ) );
	};
	$scope.addingGeotrack = false;
	$scope.addGeotrack = function() {
		$scope.addingGeotrack = true;
		console.log( 'addGeotrack...' );
	};
	$scope.closeModal = function() {
		$scope.addingGeotrack = false;
	};
	$scope.addGeotracks = function( tracks ) {
		$scope.addingGeotrack = false;
		console.log( 'add ' + tracks.length + ' track(s)' );
		for (var ti in tracks) {
			// Don't carry over selected or $$hashKey.
			var track = jQuery.extend( {}, tracks[ti] );
			delete track['selected'];
			delete track['$$hashKey'];
			var entry = { track: track };
			$scope.geolist.push( entry );
		}
		$scope.changed();
	}
}]);

gtedApp.controller('search', ['$scope', '$http', 'gted_wp', '$httpParamSerializer', function($scope,$http,gted_wp,$httpParamSerializer) {
	// Search geotrack.
	$scope.searching = false;
	$scope.searchText = '';
	$scope.searchPersonal = false;
	$scope.searchPersonalOptions = [{
		value: true,
		label: 'Personal'
	},{
		value: false,
		label: 'Public'
	}];
	$scope.searchChanged = function() {
		$scope.moreGeotracks = false;
	};
	$scope.searchKey = function( $event ) {
		if ( $event.which == 13 ) {
			$scope.search();
			$event.preventDefault();
		}
	};
	$scope.geotracks = [];
	$scope.moreGeotracks = false;
	$scope.search = function() {
		$scope.selectedCount = 0;
		$scope.searching = true;
		$scope.geotracks.splice( 0, $scope.geotracks.length );
		$scope.moreGeotracks = false;
		$scope.more();
	}
	$scope.more = function() {
		$scope.moreGeotracks = false;
		var query = {
			personal: $scope.searchPersonal,
			text: $scope.searchText,
			offset: $scope.geotracks.length
		};
		console.log( 'search for ' + $scope.searchText + ', ' + ( $scope.searchPersonal ? 'personal': 'public' ) );
		var data = {
			_ajax_nonce: gted_wp.nonce,
			action: 'gted_search_geotracks',
			data: query
		};
		console.log( 'request is ' + JSON.stringify( data ) );
		$http({
			method: 'POST',
			url: gted_wp.ajax_url,
			data: $httpParamSerializer( data ),
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).then(
			function(response) {
				$scope.searching = false;
				if ( '0' === response.data ) {
					console.log( 'search response 0 - unknown entry point?' );
					return;
				}
				if ( '-1' === response.data ) {
					console.log( 'search response -1 - error, e.g. validation' );
					return;
				}
				if ( true !== response.data.success ) {
					console.log( 'search failed: ' + response.data.data );
					return;
				}
				// Expect array of {id:,title:}.
				for (var gi = 0; gi < 10 && gi < response.data.data.length; gi++) {
					$scope.geotracks.push( response.data.data[gi] );
				}
				$scope.moreGeotracks = response.data.data.length > 10;
				console.log( 'search response ' + JSON.stringify( response.data ) );
			}, function(response) {
				$scope.searching = false;
				console.log( 'search error ' + response.statusText + ' (' + JSON.stringify( response.data ) + ')' );
			}
		);
	};
	$scope.selectedCount = 0;
	$scope.changeSelected = function() {
		$scope.selectedCount = 0;
		for (var gi in $scope.geotracks) {
			if ( $scope.geotracks[gi].selected ) {
				$scope.selectedCount++; }
		}
	};
	$scope.insert = function() {
		$scope.addingGeotrack = false;
		var newtracks = [];
		for (var gi in $scope.geotracks) {
			var geotrack = $scope.geotracks[gi];
			if (geotrack.selected) {
				newtracks.push( geotrack ); }
		}
		// Inherit.
		$scope.addGeotracks( newtracks );
	}
}]);
