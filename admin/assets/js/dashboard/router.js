// Filename: router.js
define([
	'views/main'
	], function( MainView ){
		 
		var AppRouter = Backbone.Router.extend({
			routes: {
				// Define some URL routes
				'': 'defaultAction'
			},
			defaultAction : function( actions ){
				
				var view = new MainView();
				
				view.render();
               
			   

			}
		});
		var initialize = function(){
			
			routerModule = new AppRouter();
			Backbone.history.start();
		};
		return {
			initialize: initialize
		};	
	});
