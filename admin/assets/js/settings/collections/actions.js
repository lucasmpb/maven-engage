define(['models/action' ],function( Action ){
	
	var Actions = Backbone.Collection.extend({
		action:'entryActionPoint',
		model: Action
		
	});

	
	return Actions;

});
