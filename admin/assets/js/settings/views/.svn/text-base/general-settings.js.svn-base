define(['text!templates/general-settings.html', 'localization', 'jquery', 'tagsInput']
	, function(GeneralSettingsTlt, localization, $) {

		var GeneralSettingsView = Backbone.View.extend({
			translation: null,
			template: _.template(GeneralSettingsTlt),
			/* Bind controls to model attributes */
			bindings: {
				'#shopSlugPrefix': 'shopSlugPrefix',
				'#shopSlug': 'shopSlug',
				'#wholesaleRole': {
					observe: "wholesaleRole",
					selectOptions: {
						collection: function() {
							// Prepend null or undefined for an empty select option and value.
							return CachedRoles;
						}
					}
				},
				'#emailNotificationsTo': 'emailNotificationsTo'
			},
			events: {
				'click #save': 'save'
			},
			save: function() {

				this.model.save();

			},
			initialize: function(options) {

			},
			render: function() {

				this.$el.html(this.template(localization.toJSON()));

				this.stickit();

				return this;

			}

		});
		return GeneralSettingsView;
	})
