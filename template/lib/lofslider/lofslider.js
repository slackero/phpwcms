/**
 * LofSliderNews
 * Copyright (c) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * This plugin is dual-licensed under the GNU General Public License and the MIT License
 * http://landofcoder.com/our-porfolios/jquery-plugins/item/10-the-lof-jsidernews-plugin.html
 */
(function($) {
	 $.fn.lofJSidernews = function( settings ) {
	 	return this.each(function() {
			// get instance of the lofSiderNew.
			new  $.lofSidernews( this, settings ); 
		});
 	 }
	 $.lofSidernews = function( obj, settings ){
		this.settings = {
			direction	    	: '',
			mainItemSelector    : 'li',
			navInnerSelector	: 'ul',
			navSelector  		: 'li' ,
			navigatorEvent		: 'click',
			wapperSelector: 	'.lof-main-wapper',
			interval	  	 	: 4000,
			auto			    : true, // whether to automatic play the slideshow
			maxItemDisplay	 	: 3,
			startItem			: 0,
			navPosition			: 'vertical', 
			navigatorHeight		: 100,
			navigatorWidth		: 310,
			duration			: 600,
			navItemsSelector    : '.lof-navigator li',
			navOuterSelector    : '.lof-navigator-outer' ,
			isPreloaded			: true,
			easing				: 'easeInOutQuad'
		}	
		$.extend( this.settings, settings ||{} );	
		this.nextNo         = null;
		this.previousNo     = null;
		this.maxWidth  = this.settings.mainWidth || 600;
		this.wrapper = $( obj ).find( this.settings.wapperSelector );	
		this.slides = this.wrapper.find( this.settings.mainItemSelector );
		if( !this.wrapper.length || !this.slides.length ) return ;
		// set width of wapper
		if( this.settings.maxItemDisplay > this.slides.length ){
			this.settings.maxItemDisplay = this.slides.length;	
		}
		this.currentNo      = isNaN(this.settings.startItem)||this.settings.startItem > this.slides.length?0:this.settings.startItem;
		this.navigatorOuter = $( obj ).find( this.settings.navOuterSelector );	
		this.navigatorItems = $( obj ).find( this.settings.navItemsSelector ) ;
		this.navigatorInner = this.navigatorOuter.find( this.settings.navInnerSelector );
		
		if( this.settings.navPosition == 'horizontal' ){ 
			this.navigatorInner.width( this.slides.length * this.settings.navigatorWidth );
			this.navigatorOuter.width( this.settings.maxItemDisplay * this.settings.navigatorWidth );
			this.navigatorOuter.height(	this.settings.navigatorHeight );
			
		} else {
			this.navigatorInner.height( this.slides.length * this.settings.navigatorHeight );	
			
			this.navigatorOuter.height( this.settings.maxItemDisplay * this.settings.navigatorHeight );
			this.navigatorOuter.width(	this.settings.navigatorWidth );
		}		
		this.navigratorStep = this.__getPositionMode( this.settings.navPosition );		
		this.directionMode = this.__getDirectionMode();  
		
		
		if( this.settings.direction == 'opacity') {
			this.wrapper.addClass( 'lof-opacity' );
			$(this.slides).css('opacity',0).eq(this.currentNo).css('opacity',1);
		} else { 
			this.wrapper.css({'left':'-'+this.currentNo*this.maxSize+'px', 'width':( this.maxWidth ) * this.slides.length } );
		}

		
		if( this.settings.isPreloaded ) {
			this.preLoadImage( this.onComplete );
		} else {
			this.onComplete();
		}
		
	 }
     $.lofSidernews.fn =  $.lofSidernews.prototype;
     $.lofSidernews.fn.extend =  $.lofSidernews.extend = $.extend;
	 
	 $.lofSidernews.fn.extend({
							  
		startUp:function( obj, wrapper ) {
			seft = this;

			this.navigatorItems.each( function(index, item ){
				$(item).click( function(){
					seft.jumping( index, true );
					seft.setNavActive( index, item );					
				} );
				$(item).css( {'height': seft.settings.navigatorHeight, 'width':  seft.settings.navigatorWidth} );
			})
			this.registerWheelHandler( this.navigatorOuter, this );
			this.setNavActive(this.currentNo );
			
			if( this.settings.buttons && typeof (this.settings.buttons) == "object" ){
				this.registerButtonsControl( 'click', this.settings.buttons, this );

			}
			if( this.settings.auto ) 
			this.play( this.settings.interval,'next', true );
			
			return this;
		},
		onComplete:function(){
			setTimeout( function(){ $('.preload').fadeOut( 900 ); }, 400 );	this.startUp( );
		},
		preLoadImage:function(  callback ){
			var self = this;
			var images = this.wrapper.find( 'img' );
	
			var count = 0;
			images.each( function(index,image){ 
				if( !image.complete ){				  
					image.onload =function(){
						count++;
						if( count >= images.length ){
							self.onComplete();
						}
					}
					image.onerror =function(){ 
						count++;
						if( count >= images.length ){
							self.onComplete();
						}	
					}
				}else {
					count++;
					if( count >= images.length ){
						self.onComplete();
					}	
				}
			} );
		},
		navivationAnimate:function( currentIndex ) { 
			if (currentIndex <= this.settings.startItem 
				|| currentIndex - this.settings.startItem >= this.settings.maxItemDisplay-1) {
					this.settings.startItem = currentIndex - this.settings.maxItemDisplay+2;
					if (this.settings.startItem < 0) this.settings.startItem = 0;
					if (this.settings.startItem >this.slides.length-this.settings.maxItemDisplay) {
						this.settings.startItem = this.slides.length-this.settings.maxItemDisplay;
					}
			}		
			this.navigatorInner.stop().animate( eval('({'+this.navigratorStep[0]+':-'+this.settings.startItem*this.navigratorStep[1]+'})'), 
												{duration:500, easing:'easeInOutQuad'} );	
		},
		setNavActive:function( index, item ){
			if( (this.navigatorItems) ){ 
				this.navigatorItems.removeClass( 'active' );
				$(this.navigatorItems.get(index)).addClass( 'active' );	
				this.navivationAnimate( this.currentNo );	
			}
		},
		__getPositionMode:function( position ){
			if(	position  == 'horizontal' ){
				return ['left', this.settings.navigatorWidth];
			}
			return ['top', this.settings.navigatorHeight];
		},
		__getDirectionMode:function(){
			switch( this.settings.direction ){
				case 'opacity': this.maxSize=0; return ['opacity','opacity'];
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerWheelHandler:function( element, obj ){ 
			 element.bind('mousewheel', function(event, delta ) {
				var dir = delta > 0 ? 'Up' : 'Down',
					vel = Math.abs(delta);
				if( delta > 0 ){
					obj.previous( true );
				} else {
					obj.next( true );
				}
				return false;
			});
		},
		registerButtonsControl:function( eventHandler, objects, self ){ 
			for( var action in objects ){ 
				switch (action.toString() ){
					case 'next':
						objects[action].click( function() { self.next( true) } );
						break;
					case 'previous':
						objects[action].click( function() { self.previous( true) } );
						break;
				}
			}
			return this;	
		},
		onProcessing:function( manual, start, end ){	 		
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);				
			return this;
		},
		finishFx:function( manual ){
			if( manual ) this.stop();
			if( manual && this.settings.auto ){ 
				this.play( this.settings.interval,'next', true );
			}		
			this.setNavActive( this.currentNo );	
		},
		getObjectDirection:function( start, end ){
			return eval("({'"+this.directionMode[0]+"':-"+(this.currentNo*start)+"})");	
		},
		fxStart:function( index, obj, currentObj ){
				if( this.settings.direction == 'opacity' ) { 
					$(this.slides).stop().animate({opacity:0}, {duration: this.settings.duration, easing:this.settings.easing} );
					$(this.slides).eq(index).stop().animate( {opacity:1}, {duration: this.settings.duration, easing:this.settings.easing} );
				}else {
					this.wrapper.stop().animate( obj, {duration: this.settings.duration, easing:this.settings.easing} );
				}
			return this;
		},
		jumping:function( no, manual ){
			this.stop(); 
			if( this.currentNo == no ) return;		
			 var obj = eval("({'"+this.directionMode[0]+"':-"+(this.maxSize*no)+"})");
			this.onProcessing( null, manual, 0, this.maxSize )
				.fxStart( no, obj, this )
				.finishFx( manual );	
				this.currentNo  = no;
		},
		next:function( manual , item){

			this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);	
			this.onProcessing( item, manual, 0, this.maxSize )
				.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ), this )
				.finishFx( manual );
		},
		previous:function( manual, item ){
			this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
			this.onProcessing( item, manual )
				.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ), this )
				.finishFx( manual	);			
		},
		play:function( delay, direction, wait ){	
			this.stop(); 
			if(!wait){ this[direction](false); }
			var self  = this;
			this.isRun = setTimeout(function() { self[direction](true); }, delay);
		},
		stop:function(){ 
			if (this.isRun == null) return;
			clearTimeout(this.isRun);
            this.isRun = null; 
		}
	})
})(jQuery)