define(['jquery','localization','text!templates/tabs.html',
		'views/general-settings',
		'views/actions-settings'
	]
	,function( $,localization, tabsTlt, GeneralSettingsView, ActionsSettingsView ){
		
		var TabsView = Backbone.View.extend({

			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			template: _.template(tabsTlt),
			settingsView:null,
			actionsView:null,
			settings:null,
			// Constructor
			initialize: function( options ) {
			    
				//this.model.bind('change', this.render);
				this.settings = options.settings;
				
			},
			
			events: {
				
			},

			
			render: function(){
				//var self = this;
				
				this.$el.html(this.template(localization.toJSON()));
				
				//return this;
				// We need to create the tabs elements.
				
				this.settingsView = new GeneralSettingsView( {  model: this.settings.getGeneralSetting( ) } );
				this.actionsView = new ActionsSettingsView({model:this.settings.getActionsSetting()});
				
				//generalTab.html(settings.render().el );
				this.settingsView.setElement(this.$('#tabs-general')).render();
				this.actionsView.setElement(this.$('#tabs-actions')).render();
				
				return this;
			}

		});
		return TabsView;
	})
