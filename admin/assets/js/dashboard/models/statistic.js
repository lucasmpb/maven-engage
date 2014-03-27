define([], function() {

	var Statistic = Backbone.Model.extend({
		action: 'entryPoint',
		defaults: {
			sent: 0,
			recover: 0,
			completed: 0,
			recoverPercent:0,
			completedPercent:0

		},
		// Constructor
		initialize: function() {

		},
		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}

	});

	return Statistic;

});