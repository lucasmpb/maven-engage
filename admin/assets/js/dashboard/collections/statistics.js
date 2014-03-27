define(['models/statistic','pageable'],function( Statistic ){
	
	var Statistics = Backbone.MavenCollection.extend({
		model: Statistic,
		action:'entryPoint',
		initialize: function() {			
		}
	});
	
	return Statistics;
	
});

