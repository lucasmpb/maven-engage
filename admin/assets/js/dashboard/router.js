// presenters/app/router.js
define(function(require) {
	var $ = require('jquery'),
		Statistics = require('collections/statistics'),
		StatisticView = require('views/statistic'),
		notifications = require('notifications'),
		spinner = require('spinner');

	return Backbone.Router.extend({
		collection: null,
		routes: {
			'statistics/:from/:to': 'defaultRoute',
			'*path': 'defaultRoute'
		},
		initialize: function(options) {
			this.el = options.el;
			this.collection = new Statistics();
		},
		removeParams: function() {
			delete this.collection.queryParams['fromDate'];
			delete this.collection.queryParams['toDate'];
		},
		defaultRoute: function(fromDate, toDate) {
			this.removeParams();
			if (fromDate)
				this.collection.queryParams['fromDate'] = fromDate;
			if (toDate)
				this.collection.queryParams['toDate'] = toDate;

			//spinner.stop();
			var self = this;
			//Fetch the data from the server
			this.collection.fetch({
				success: function(collection) {
					$(self.el).html(new StatisticView({
						model: collection.first(),
						fromDate:fromDate,
						toDate:toDate
					}).el);
				},
				failure: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		}
	});
});


