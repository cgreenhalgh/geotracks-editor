<div ng-app="gted" ng-controller="test">
        <div><input type="text" ng-model="test" ng-change="update()"></div>
        <div>
        	<div class="geolist-entry" ng-repeat="geolist_entry in geolist">
        		<span class="geotrack-title">{{ geolist_entry.track.title }}</span>
        	</div>
        </div>
        <button type="button" class="button" ng-click="addGeotrack()">Add Geotrack</button>

	<div class="gted-modal" ng-class="{hidden:(!addingGeotrack)}">
		<div class="media-modal">
			<button type="button" class="button-link media-modal-close" ng-click="closeModal()"><span class="media-modal-icon"><span class="screen-reader-text">Close Geotrack panel</span></span></button>
			<div class="media-modal-content">
				<div class="media-frame" ng-controller="search">
					<div class="media-frame-title">
						<h1>Add Geotrack</h1>
					</div>
					<div class="media-frame-content" >
						<div class="inside">
						<input type="text" placeholder="Title" ng-keypress="searchKey($event)" ng-change="searchChanged()" ng-model="searchText">
						<button type="button" class="button" ng-click="search()">Search</button>
						<select ng-model="searchPersonal" ng-options="item.value as item.label for item in searchPersonalOptions" ng-change="searchChanged()"></select>
						<span class="gted-loading" ng-class="{hidden: !searching}">searching...</span><br>
						<table><tbody>
							<tr ng-repeat="geotrack in geotracks"><td><label><input type="checkbox" ng-change="changeSelected()" ng-model="geotrack.selected">{{ geotrack.title }}</label></td></tr>
							<tr ng-class="{hidden: !moreGeotracks}"><td><a ng-click="more()">More...</a></td></tr>
						</tbody></table>
						</div>
					</div>
					<div class="media-frame-toolbar">
						<div class="media-toolbar">
							<div class="media-toolbar-primary">
								<button type="button" class="button button-primary" ng-disabled="selectedCount==0" ng-click="insert()">Insert into Geolist</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="media-modal-backdrop"></div>
	</div>
</div>
