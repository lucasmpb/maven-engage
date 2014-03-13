var MavenJS = MavenJS || {};


MavenJS.Message = function(){
	this.successful	= false;
	this.error		= false;
	this.warning	= false;
	this.none		= true;
	this.description= "";
};

MavenJS.Shop = {};

MavenJS.Shop.PluginKey = function(){
	return 'mavenshop';
};

MavenJS.Ajax = function (){
	
	var $ = jQuery.noConflict();
	
	this.vars = {};
	this.URLString = "";
	this.onCompletion = null;
	this.onError = null;
	this.async = true;
	this.calledFrom = 'admin';
	this.jsonObj = null;
	this.cache = true;
	
	_this = null;

	this.setAsync = function(value){
		if(typeof(value) == 'boolean')
			this.async = value;
	};
	this.setVar = function(key,value){
		//this.vars[key] = encodeURIComponent(value);
		this.vars[key] = value;
	};
	this.setCalledFrom = function(value){
		this.calledFrom = value;
	};
	
	this.getVarsSerialized = function(){
		return $.param(this.vars);
	};
	
	
	this.execute = function(action){
		
		this.setVar('action', action);
		this.setVar('calledFrom', this.calledFrom);
		_this = this;
	
		$.ajax({
			url: Maven.ajaxUrl,
			cache: this.cache,
			type: 'POST',
			dataType: 'json',
			async: this.async,
			data: this.getVarsSerialized(),
			success: function(data){

				if(undefined != data && typeof(data) == 'object' && undefined != data.is_error){
		    
					// Check if the user wants to execute another function
					if (this.onCompletion)
						this.onCompletion(data);
					else
						this.showMessage(data);
				}
					
			}

		});
	};
};

MavenJS.Shop.AddProductToCart = function( product ){
	
	var result = new MavenJS.Message();
	var thingObj = {thing:{pluginKey:'',id:''}, 
					step:{action:''}};
	
	if ( ! product.id ){
		result.error = true;
		result.description = "Missing Thing ID";
		return result;
	}
	
	thingObj.thing.pluginKey = MavenJS.Shop.PluginKey();
	thingObj.thing.id = product.id;
	thingObj.step.action = 'addToCart';
	
	var ajaxCall = new MavenJS.Ajax();
	ajaxCall.setVar('mvn',thingObj);
	ajaxCall.setVar('mavenTransactionKey', Maven.transactionNonce);
	ajaxCall.execute('mavenAjaxCartHandler');
};

//
//
//<input type="hidden" name="mvn[thing][id]" value="<?php echo $product->getId(); ?>" />
//		<input type="hidden" name="mvn[thing][pluginKey]" value="<?php echo \MavenShop\Settings\ShopRegistry::instance()->getPluginKey(); ?>" />
//		<input type='hidden' name="mvn[step][action]" value='<?php echo \Maven\Front\Actions::AddToCart ?>' />
//		<input type='hidden' name="mvn[step][onComplete][redirect]" value='merchandise-contact-info' />

 