define(['jquery', 'text!templates/main.html', 'spinner', 'localization', ]
	, function( $, MainTlt, spinner, localization ) {

		var MainView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			el: '#mainContainer',
			template: _.template(MainTlt),
			events: {
				
			},
			// Constructor
			initialize: function() {
				_.bindAll(this);
				
			},
			render: function() {
				
				this.$el.html(this.template(localization.toJSON()));


				spinner.stop();

				return this;
			}
			
		});
		return MainView;
	});
