/*
	Slimbox v1.56 - The ultimate lightweight Lightbox clone
	(c) 2007-2008 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
	
	2009-01-20 Oliver Georgi
	- Implement Math.round for better centered position
	- Add Browser based language options for counterText
	- Enhance lbPrevLink, lbNextLink and lbCloseLink by class names lbPrevLink-{browserLang} and so on (check DOM)
*/
var Slimbox=(function(){var F=window,u,h,G,w,E,v,y,M,s,l=q.bindWithEvent(),f=window.opera&&(navigator.appVersion>="9.3"),p=document.documentElement,o={},t=new Image(),K=new Image(),I,a,i,J,e,H,c,A,L,x,j,d,C;F.addEvent("domready",function(){$(document.body).adopt($$(I=new Element("div",{id:"lbOverlay"}),a=new Element("div",{id:"lbCenter"}),H=new Element("div",{id:"lbBottomContainer"})).setStyle("display","none"));i=new Element("div",{id:"lbImage"}).injectInside(a).adopt(J=new Element("a",{id:"lbPrevLink",href:"#"}),e=new Element("a",{id:"lbNextLink",href:"#"}));J.onclick=B;e.onclick=g;var N;c=new Element("div",{id:"lbBottom"}).injectInside(H).adopt(N=new Element("a",{id:"lbCloseLink",href:"#"}),A=new Element("div",{id:"lbCaption"}),L=new Element("div",{id:"lbNumber"}),new Element("div",{styles:{clear:"both"}}));N.onclick=I.onclick=D});function z(){var O=F.getScrollLeft(),N=f?p.clientWidth:F.getWidth();$$(a,H).setStyle("left",O+Math.round(N/2));if(v){I.setStyles({left:O,top:F.getScrollTop(),width:N,height:F.getHeight()})}}function n(N){["object",F.ie6?"select":"embed"].forEach(function(P){$each(document.getElementsByTagName(P),function(Q){if(N){Q._slimbox=Q.style.visibility}Q.style.visibility=N?"hidden":Q._slimbox})});I.style.display=N?"":"none";var O=N?"addEvent":"removeEvent";F[O]("scroll",z)[O]("resize",z);document[O]("keydown",l)}function q(O){var N=O.code;if(u.closeKeys.contains(N)){D()}else{if(u.nextKeys.contains(N)){g()}else{if(u.previousKeys.contains(N)){B()}}}O.stop()}function B(){return b(w)}function g(){return b(E)}function b(N){if(N>=0){G=N;w=(G||(u.loop?h.length:0))-1;E=((G+1)%h.length)||(u.loop?0:-1);r();a.className="lbLoading";o=new Image();o.onload=m;o.src=h[N][0]}return false}function m(){a.className="";d.set(0);i.setStyles({width:o.width,backgroundImage:"url("+o.src+")",display:""});$$(i,J,e).setStyle("height",o.height);A.setHTML(h[G][1]||"");L.setHTML((((h.length>1)&&u.counterText)||"").replace(/{x}/,G+1).replace(/{y}/,h.length));J.className="lbPrevLink-"+u.cLang;e.className="lbNextLink-"+u.cLang;if(w>=0){t.src=h[w][0]}if(E>=0){K.src=h[E][0]}M=i.offsetWidth;s=i.offsetHeight;var N=Math.max(0,y-(s/2));if(a.offsetHeight!=s){j.chain(j.start.pass({height:s,top:N},j))}if(a.offsetWidth!=M){j.chain(j.start.pass({width:M,marginLeft:Math.round(-M/2)},j))}j.chain(function(){H.setStyles({width:M,top:N+s,marginLeft:Math.round(-M/2),visibility:"hidden",display:""});d.start(1)});j.callChain()}function k(){if(w>=0){J.style.display=""}if(E>=0){e.style.display=""}C.set(-c.offsetHeight).start(0);H.style.visibility=""}function r(){o.onload=Class.empty;o.src=t.src=K.src="";j.clearChain();j.stop();d.stop();C.stop();$$(J,e,i,H).setStyle("display","none")}function D(){if(G>=0){r();G=w=E=-1;a.style.display="none";x.stop().chain(n).start(0)}return false}Element.extend({slimbox:function(N,O){$$(this).slimbox(N,O);return this}});Elements.extend({slimbox:function(N,Q,P){Q=Q||function(R){return[R.href,R.title]};P=P||function(){return true};var O=this;O.forEach(function(R){R.removeEvents("click").addEvent("click",function(S){var T=O.filter(P,this);Slimbox.open(T.map(Q),T.indexOf(this),N);S.stop()}.bindWithEvent(R))});return O}});return{open:function(P,O,N){u=$extend({loop:false,overlayOpacity:0.8,overlayFadeDuration:400,resizeDuration:400,resizeTransition:false,initialWidth:250,initialHeight:250,imageFadeDuration:400,captionAnimationDuration:400,counterText:"Image {x} of {y}",cLang:"en",closeKeys:[27,88,67,83],previousKeys:[37,80,90],nextKeys:[39,78,87]},N||{});x=I.effect("opacity",{duration:u.overlayFadeDuration});j=a.effects($extend({duration:u.resizeDuration},u.resizeTransition?{transition:u.resizeTransition}:{}));d=i.effect("opacity",{duration:u.imageFadeDuration,onComplete:k});C=c.effect("margin-top",{duration:u.captionAnimationDuration});if(typeof P=="string"){P=[[P,O]];O=0}y=F.getScrollTop()+((f?p.clientHeight:F.getHeight())/2);M=u.initialWidth;s=u.initialHeight;a.setStyles({top:Math.max(0,y-(s/2)),width:M,height:s,marginLeft:Math.round(-M/2),display:""});v=F.ie6||(I.currentStyle&&(I.currentStyle.position!="fixed"));if(v){I.style.position="absolute"}x.set(0).start(u.overlayOpacity);z();n(1);h=P;u.loop=u.loop&&(h.length>1);return b(O)}}})();

// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
Slimbox.scanPage = function() {
	// OG: enhanced for translated image cpations
	var bLang=navigator.language?navigator.language:navigator.userLanguage;
	if(bLang){bLang=bLang.substr(0,2);bLang=bLang.toLowerCase();}else{bLang = 'en';}
	var cText="Image {x} of {y}";
	switch(bLang) {
		case 'de':	cText = "Bild {x} von {y}"; break;
		case 'es':	cText = "Imagen {x} de {y}"; break;
		case 'fr':	cText = "Image {x} de {y}"; break;
		case 'nl':	cText = "Afbeelding {x} van {y}"; break;
		case 'da':	cText = "billede {x} fra {y}"; break;
	}
	// OG: end enhancement

	$$($$(document.links).filter(function(el) {
		return el.rel && el.rel.test(/^lightbox/i);
	})).slimbox({counterText: cText, cLang: bLang /* Put custom options here */}, null, function(el) {
		// OG: sorry I am too stupid to find the correct var, 
		// so use brute force method to inject the class name
		$("lbCloseLink").className="lbCloseLink-"+bLang;
		return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
	});
	
};
window.addEvent("domready", Slimbox.scanPage);