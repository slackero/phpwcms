var IframeShim=new Class({Implements:[Options,Events,Class.Occlude],options:{className:"iframeShim",src:'javascript:false;document.write("");',display:false,zIndex:null,margin:0,offset:{x:0,y:0},browsers:(Browser.Engine.trident4||(Browser.Engine.gecko&&!Browser.Engine.gecko19&&Browser.Platform.mac))},property:"IframeShim",initialize:function(b,a){this.element=document.id(b);if(this.occlude()){return this.occluded}this.setOptions(a);this.makeShim();return this},makeShim:function(){if(this.options.browsers){var c=this.element.getStyle("zIndex").toInt();if(!c){c=1;var b=this.element.getStyle("position");if(b=="static"||!b){this.element.setStyle("position","relative")}this.element.setStyle("zIndex",c)}c=($chk(this.options.zIndex)&&c>this.options.zIndex)?this.options.zIndex:c-1;if(c<0){c=1}this.shim=new Element("iframe",{src:this.options.src,scrolling:"no",frameborder:0,styles:{zIndex:c,position:"absolute",border:"none",filter:"progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)"},"class":this.options.className}).store("IframeShim",this);var a=(function(){this.shim.inject(this.element,"after");this[this.options.display?"show":"hide"]();this.fireEvent("inject")}).bind(this);if(IframeShim.ready){window.addEvent("load",a)}else{a()}}else{this.position=this.hide=this.show=this.dispose=$lambda(this)}},position:function(){if(!IframeShim.ready||!this.shim){return this}var a=this.element.measure(function(){return this.getSize()});if(this.options.margin!=undefined){a.x=a.x-(this.options.margin*2);a.y=a.y-(this.options.margin*2);this.options.offset.x+=this.options.margin;this.options.offset.y+=this.options.margin}this.shim.set({width:a.x,height:a.y}).position({relativeTo:this.element,offset:this.options.offset});return this},hide:function(){if(this.shim){this.shim.setStyle("display","none")}return this},show:function(){if(this.shim){this.shim.setStyle("display","block")}return this.position()},dispose:function(){if(this.shim){this.shim.dispose()}return this},destroy:function(){if(this.shim){this.shim.destroy()}return this}});window.addEvent("load",function(){IframeShim.ready=true});