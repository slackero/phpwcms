/**
  switchClass
  ~~~~~~~~~~~
  @author  Ben Keen, http://www.benjaminkeen.com/software/jquery_switchClass
  @version 1.0
  @date    April 18 2008
**/
(function($){var class1,class2,overrideClass=null;$.fn.switchClass=function(){if(arguments.length<2){alert("Illegal usage. switchClass requires at least 2 parameters, containing the class names to toggle.");return this}class1=arguments[0];class2=arguments[1];overrideClass=null;if(arguments.length==3)overrideClass=arguments[2];return this.each(function(){$.fn.switchClass.process($(this))})};$.fn.switchClass.process=function(el){if(overrideClass!=null){if(overrideClass==class1&&el.hasClass(class2)){el.removeClass(class2);el.addClass(class1)}else if(overrideClass==class2&&el.hasClass(class1)){el.removeClass(class1);el.addClass(class2)}}else{if(el.hasClass(class1)){el.removeClass(class1);el.addClass(class2)}else if(el.hasClass(class2)){el.removeClass(class2);el.addClass(class1)}}}})(jQuery);