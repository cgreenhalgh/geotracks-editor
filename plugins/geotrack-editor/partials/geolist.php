<div ng-app="gted" ng-controller="test">
        <div><input type="text" ng-model="test" ng-change="update()"></div>
        <button type="button" class="button" ng-click="addGeotrack()">Add Geotrack</button>

	<div class="gted-modal" ng-class="{hidden:(!addingGeotrack)}">
		<div class="media-modal">
			<button type="button" class="button-link media-modal-close" ng-click="closeModal()"><span class="media-modal-icon"><span class="screen-reader-text">Close Geotrack panel</span></span></button>
			<div class="media-modal-content">
				<div class="media-frame">
					<div class="media-frame-title">
						<h1>Add Geotrack</h1>
					</div>
					<div class="media-frame-content">
						<div class="inside">
						<input type="text" placeholder="Title" ng-keypress="searchKey($event)" ng-change="searchChanged()" ng-model="searchText">
						<button type="button" class="button" ng-click="search()">Search</button>
						<span class="gted-loading" ng-class="{hidden: !searching}">searching...</span><br>
						</div>
					</div>
					<div class="media-frame-toolbar">
						<div class="media-toolbar">
							<div class="media-toolbar-primary">
								<button type="button" class="button button-primary">Insert into Geolist</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="media-modal-backdrop"></div>
	</div>
</div>
