var networkApp = angular.module('networkApp', ['ngResource']);

networkApp.config(function($routeProvider) {
$routeProvider.
	when('/', {controller: 'AppCtrl', templateUrl: 'partials/list.phtml'}).
	when('/about', {templateUrl: 'partials/about.phtml'}).
	otherwise({redirectTo: '/'});
});

networkApp.controller('AppCtrl', function($scope, Item) {

	var items = $scope.items = Item.query();

	// event handler
	$scope.add = function(newItem) {
		var item = new Item({message: newItem.message});
		items.push(item);
		newItem.message = '';

		// save to services
		item.$save();
	};

	$scope.reply = function(newItem, parentId)
	{
		var item = new Item({message: newItem.message, parent_id: parentId});

		for (i = 0; i < items.length; i++)
		{
			if (items[i].id == parentId)
			{
				items[i].replies.push(item);
			}
		}

		newItem.message = '';

		item.$save();
	}

	$scope.update = function(updatedItem) {
		var item = new Item(updatedItem);
		item.$update();
	}
});

networkApp.factory('Item', function($resource) {
	var Item = $resource('http://localhost/~pasamio/research/mosc/app/www/api/messages/:message_id',  {
		message_id: "@message_id"
	}, {
		update: {method: 'PUT'}
	});

	Item.prototype.state = false;
	Item.prototype.parent_id = '';
	Item.prototype.message = '';
	Item.prototype.replies = [];

	return Item;
});
