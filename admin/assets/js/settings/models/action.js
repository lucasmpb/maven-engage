define([],function(){
	
	var Action = Backbone.Model.extend({
		action:'entryPoint',	
		defaults: {
			
			name: "",
			description: "",
			disabledClass:""
			
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return Action;

});
