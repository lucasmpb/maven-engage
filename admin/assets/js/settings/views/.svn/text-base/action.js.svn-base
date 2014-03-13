define([ 'text!templates/action.html','localization' ,'jquery' ]
	,function(  ActionTlt ,localization, $ ){
		
		var ActionView = Backbone.View.extend({

			template: _.template( ActionTlt ),
			/* Bind controls to model attributes */
			bindings: {
				
			},
			events: {
				'click .toggle-thumb':'toggle'
			},
			toggle:function( ev ){
				if ( this.$( ev.target ).parent().hasClass('disabled')  )
					this.model.set('enabled',false);
				else
					this.model.set('enabled',true);
			},
			initialize: function( options ) {
				
			},
			
			render: function(){
				
				if ( this.model.get('enabled') ){
					this.model.set('disabledClass','enabled');
				}
				else{
					this.model.set('disabledClass','disabled');
				}
					
				this.$el.html(this.template( this.model.toJSON() ) );
				
				//this.$el.html(this.template( localization.toJSON() ) );
				
					
				return this;
			
			}

		});
		
		return ActionView;
	})
