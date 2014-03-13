define([ 'text!templates/actions-settings.html','localization' ,'jquery','collections/actions','views/action','tagsInput', ]
	,function(  GeneralSettingsTlt ,localization, $, Actions, ActionView){
		
		var GeneralSettingsView = Backbone.View.extend({

			translation: null,
			template: _.template( GeneralSettingsTlt ),
			actions:null,
			/* Bind controls to model attributes */
			bindings: {
				
			},
			events: {
				'click #save':'save'
			},
			save:function(){
				
				//Clean the actions
				this.model.set('actions','');
				
				actions = new Array();
				
				this.actions.each( function( action ) {
					
					if ( action.get('enabled') )
						actions.push( action.get('action') );
				});
				
				this.model.set('actions',actions);
				
				this.model.save();
				
				//console.log(this.actions);
				
				//this.model.update();
			} ,
			initialize: function( options ) {
				
				this.actions = new Actions();
				this.actions.reset( SavedActions );
				
			},
			
			render: function(){

				that = this;
				
				this.$el.html(this.template(localization.toJSON()));
				
				actionList = this.$('#action-list');
				
				this.actions.each(function( action ){
					
					var actionView = new ActionView({
						model: action
					});
					
					actionList.append(actionView.render().el);
				});
				
				this.$('.toggle').click(function() {
				   $(this).toggleClass('disabled');
				   $(this).parent('.item').toggleClass('disabled');
				   if ($('.toggle').parent('.item').is(':not(.disabled)')){
					   $('.maven-modal').removeClass('active');
				   } else {
					   $('.maven-modal').addClass('active');
				   }
			   });

				return this;
			
			}

		});
		return GeneralSettingsView;
	})
