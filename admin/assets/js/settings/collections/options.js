define(['models/option', 'models/general-setting'], function(Option, GeneralSetting) {

	var Options = Backbone.Collection.extend({
		action: 'entryPoint',
		model: Option,
		printAll: function() {
			_.each(this.models, function(model) {
				console.log(model.get('name'));
			});
		},
		getGeneralSetting: function() {
			var gs = new GeneralSetting();

			var emailNotificationsTo = this.get('emailNotificationsTo');
			
			gs.set('emailNotificationsTo', emailNotificationsTo.get('value'));
			gs.set('id', '1');

			return gs;
		},
		getActionsSetting: function() {
			var gs = new GeneralSetting();

			var actions = this.get('actions');

			gs.set('actions', actions.get('value'));

			gs.set('id', '1');
			return gs;
		}

	});


	return Options;

});
