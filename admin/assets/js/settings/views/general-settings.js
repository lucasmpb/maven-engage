define(['text!templates/general-settings.html', 'localization', 'jquery', 'tagsInput', 'toggleButtons']
	, function(GeneralSettingsTlt, localization, $) {

		var GeneralSettingsView = Backbone.View.extend({
			translation: null,
			template: _.template(GeneralSettingsTlt),
			/* Bind controls to model attributes */
			bindings: {
				'#emailNotificationsTo': 'emailNotificationsTo',
				'#enabled':'enabled'
			},
			events: {
				'click #save': 'save'
			},
			save: function() {

				this.model.save();

			},
			initialize: function(options) {
				console.log(this.model);
			},
			render: function() {

				this.$el.html(this.template(localization.toJSON()));

				this.stickit();
				
				/*Important: First bind stickit, then apply toggleButton*/
				this.$('.toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: localization.get('yes'),
						disabled: localization.get('no')
					}
				});

				return this;

			}

		});
		return GeneralSettingsView;
	})
