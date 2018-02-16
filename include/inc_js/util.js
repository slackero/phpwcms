/*===================================================================
 Author: Matt Kruse

 View documentation, examples, and source code at:
     http://www.JavascriptToolbox.com/

 NOTICE: You may use this code for any purpose, commercial or
 private, without any further permission from the author. You may
 remove this notice from your final code if you wish, however it is
 appreciated by the author if at least the web site address is kept.

 This code may NOT be distributed for download from script sites,
 open source CDs or sites, or any other distribution method. If you
 wish you share this code with others, please direct them to the
 web site above.

 Pleae do not link directly to the .js files on the server above. Copy
 the files to your own server for use with your site or webapp.
 ===================================================================*/
/* ******************************************************************* */
/*   UTIL FUNCTIONS                                                    */
/* ******************************************************************* */
var Util = {'$VERSION':1.06};

// Util functions - these are GLOBAL so they
// look like built-in functions.

// Determine if an object is an array
function isArray(o) {
    return (o!=null && typeof(o)=="object" && typeof(o.length)=="number" && (o.length==0 || defined(o[0])));
}

// Determine if an object is an Object
function isObject(o) {
    return (o!=null && typeof(o)=="object" && defined(o.constructor) && o.constructor==Object && !defined(o.nodeName));
}

// Determine if a reference is defined
function defined(o) {
    return (typeof(o)!="undefined");
}

// Iterate over an array, object, or list of items and run code against each item
// Similar functionality to Perl's map() function
function map(func) {
    var i,j,o;
    var results = [];
    if (typeof(func)=="string") {
        func = new Function('$_',func);
    }
    for (i=1; i<arguments.length; i++) {
        o = arguments[i];
        if (isArray(o)) {
            for (j=0; j<o.length; j++) {
                results[results.length] = func(o[j]);
            }
        }
        else if (isObject(o)) {
            for (j in o) {
                results[results.length] = func(o[j]);
            }
        }
        else {
            results[results.length] = func(o);
        }
    }
    return results;
}

// Set default values in an object if they are undefined
function setDefaultValues(o,values) {
    if (!defined(o) || o==null) {
        o = {};
    }
    if (!defined(values) || values==null) {
        return o;
    }
    for (var val in values) {
        if (!defined(o[val])) {
            o[val] = values[val];
        }
    }
    return o;
}

/* ******************************************************************* */
/*   DEFAULT OBJECT PROTOTYPE ENHANCEMENTS                             */
/* ******************************************************************* */
// These functions add useful functionality to built-in objects
Array.prototype.contains = function(o) {
    var i,l;
    if (!(l = this.length)) { return false; }
    for (i=0; i<l; i++) {
        if (o==this[i]) {
            return true;
        }
    }
};

/* ******************************************************************* */
/*   DOM FUNCTIONS                                                     */
/* ******************************************************************* */
var DOM = (function() {
    var dom = {};

    // Get a parent tag with a given nodename
    dom.getParentByTagName = function(o,tagNames) {
        if(o==null) { return null; }
        if (isArray(tagNames)) {
            tagNames = map("return $_.toUpperCase()",tagNames);
            while (o=o.parentNode) {
                if (o.nodeName && tagNames.contains(o.nodeName)) {
                    return o;
                }
            }
        }
        else {
            tagNames = tagNames.toUpperCase();
            while (o=o.parentNode) {
                if (o.nodeName && tagNames==o.nodeName) {
                    return o;
                }
            }
        }
        return null;
    };

    // Remove a node from its parent
    dom.removeNode = function(o) {
        if (o!=null && o.parentNode && o.parentNode.removeChild) {
            // First remove all attributes which are func references, to avoid memory leaks
            for (var i in o) {
                if (typeof(o[i])=="function") {
                    o[i] = null;
                }
            }
            o.parentNode.removeChild(o);
            return true;
        }
        return false;
    };

    // Get the outer width in pixels of an object, including borders, padding, and margin
    dom.getOuterWidth = function(o) {
        if (defined(o.offsetWidth)) {
            return o.offsetWidth;
        }
        return null;
    };

    // Get the outer height in pixels of an object, including borders, padding, and margin
    dom.getOuterHeight = function(o) {
        if (defined(o.offsetHeight)) {
            return o.offsetHeight;
        }
        return null;
    };

    // Resolve an item, an array of items, or an object of items
    dom.resolve = function() {
        var results = new Array();
        var i,j,o;
        for (var i=0; i<arguments.length; i++) {
            var o = arguments[i];
            if (o==null) {
                if (arguments.length==1) {
                    return null;
                }
                results[results.length] = null;
            }
            else if (typeof(o)=='string') {
                if (document.getElementById) {
                    o = document.getElementById(o);
                }
                else if (document.all) {
                    o = document.all[o];
                }
                if (arguments.length==1) {
                    return o;
                }
                results[results.length] = o;
            }
            else if (isArray(o)) {
                for (j=0; j<o.length; j++) {
                    results[results.length] = o[j];
                }
            }
            else if (isObject(o)) {
                for (j in o) {
                    results[results.length] = o[j];
                }
            }
            else if (arguments.length==1) {
                return o;
            }
            else {
                results[results.length] = o;
            }
      }
      return results;
    };
    dom.$ = dom.resolve;

    return dom;
})();

/* ******************************************************************* */
/*   CSS FUNCTIONS                                                     */
/* ******************************************************************* */
var CSS = (function(){
    var css = {};

    // Convert an RGB string in the form "rgb (255, 255, 255)" to "#ffffff"
    css.rgb2hex = function(rgbString) {
        if (typeof(rgbString)!="string" || !defined(rgbString.match)) { return null; }
        var result = rgbString.match(/^\s*rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*/);
        if (result==null) { return rgbString; }
        var rgb = +result[1] << 16 | +result[2] << 8 | +result[3];
        var hex = "";
        var digits = "0123456789abcdef";
        while(rgb!=0) {
            hex = digits.charAt(rgb&0xf)+hex;
            rgb>>>=4;
        }
        while(hex.length<6) { hex='0'+hex; }
        return "#" + hex;
    };

    // Convert hyphen style names like border-width to camel case like borderWidth
    css.hyphen2camel = function(property) {
        if (!defined(property) || property==null) { return null; }
        if (property.indexOf("-")<0) { return property; }
        var str = "";
        var c = null;
        var l = property.length;
        for (var i=0; i<l; i++) {
            c = property.charAt(i);
            str += (c!="-")?c:property.charAt(++i).toUpperCase();
        }
        return str;
    };

    // Determine if an object or class string contains a given class.
    css.hasClass = function(obj,className) {
        if (!defined(obj) || obj==null || !RegExp) { return false; }
        var re = new RegExp("(^|\\s)" + className + "(\\s|$)");
        if (typeof(obj)=="string") {
            return re.test(obj);
        }
        else if (typeof(obj)=="object" && obj.className) {
            return re.test(obj.className);
        }
        return false;
    };

    // Add a class to an object
    css.addClass = function(obj,className) {
        if (typeof(obj)!="object" || obj==null || !defined(obj.className)) { return false; }
        if (obj.className==null || obj.className=='') {
            obj.className = className;
            return true;
        }
        if (css.hasClass(obj,className)) { return true; }
        obj.className = obj.className + " " + className;
        return true;
    };

    // Remove a class from an object
    css.removeClass = function(obj,className) {
        if (typeof(obj)!="object" || obj==null || !defined(obj.className) || obj.className==null) { return false; }
        if (!css.hasClass(obj,className)) { return false; }
        var re = new RegExp("(^|\\s+)" + className + "(\\s+|$)");
        obj.className = obj.className.replace(re,' ');
        return true;
    };

    // Fully replace a class with a new one
    css.replaceClass = function(obj,className,newClassName) {
        if (typeof(obj)!="object" || obj==null || !defined(obj.className) || obj.className==null) { return false; }
        css.removeClass(obj,className);
        css.addClass(obj,newClassName);
        return true;
    };

    // Get the currently-applied style of an object
    css.getStyle = function(o, property) {
        if (o==null) { return null; }
        var val = null;
        var camelProperty = css.hyphen2camel(property);
        // Handle "float" property as a special case
        if (property=="float") {
            val = css.getStyle(o,"cssFloat");
            if (val==null) {
                val = css.getStyle(o,"styleFloat");
            }
        }
        else if (o.currentStyle && defined(o.currentStyle[camelProperty])) {
            val = o.currentStyle[camelProperty];
        }
        else if (window.getComputedStyle) {
            val = window.getComputedStyle(o,null).getPropertyValue(property);
        }
        else if (o.style && defined(o.style[camelProperty])) {
            val = o.style[camelProperty];
        }
        // For color values, make the value consistent across browsers
        // Convert rgb() colors back to hex for consistency
        if (/^\s*rgb\s*\(/.test(val)) {
            val = css.rgb2hex(val);
        }
        // Lowercase all #hex values
        if (/^#/.test(val)) {
            val = val.toLowerCase();
        }
        return val;
    };
    css.get = css.getStyle;

    // Set a style on an object
    css.setStyle = function(o, property, value) {
        if (o==null || !defined(o.style) || !defined(property) || property==null || !defined(value)) { return false; }
        if (property=="float") {
            o.style["cssFloat"] = value;
            o.style["styleFloat"] = value;
        }
        else if (property=="opacity") {
            o.style['-moz-opacity'] = value;
            o.style['-khtml-opacity'] = value;
            o.style.opacity = value;
            if (defined(o.style.filter)) {
                o.style.filter = "alpha(opacity=" + value*100 + ")";
            }
        }
        else {
            o.style[css.hyphen2camel(property)] = value;
        }
        return true;
    };
    css.set = css.setStyle;

    // Get a unique ID which doesn't already exist on the page
    css.uniqueIdNumber=1000;
    css.createId = function(o) {
        if (defined(o) && o!=null && defined(o.id) && o.id!=null && o.id!="") {
            return o.id;
        }
        var id = null;
        while (id==null || document.getElementById(id)!=null) {
            id = "ID_"+(css.uniqueIdNumber++);
        }
        if (defined(o) && o!=null && (!defined(o.id)||o.id=="")) {
            o.id = id;
        }
        return id;
    };

    return css;
})();

/* ******************************************************************* */
/*   EVENT FUNCTIONS                                                   */
/* ******************************************************************* */

var Event = (function(){
    var ev = {};

    // Resolve an event using IE's window.event if necessary
    // --------------------------------------------------------------------
    ev.resolve = function(e) {
        if (!defined(e) && defined(window.event)) {
            e = window.event;
        }
        return e;
    };

    // Add an event handler to a function
    // Note: Don't use 'this' within functions added using this method, since
    // the attachEvent and addEventListener models differ.
    // --------------------------------------------------------------------
    ev.add = function( obj, type, fn, capture ) {
        if (obj.addEventListener) {
            obj.addEventListener( type, fn, capture );
            return true;
        }
        else if (obj.attachEvent) {
            obj.attachEvent( "on"+type, fn );
            return true;
        }
        return false;
    };

    // Get the mouse position of an event
    // --------------------------------------------------------------------
    // PageX/Y, where they exist, are more reliable than ClientX/Y because
    // of some browser bugs in Opera/Safari
    ev.getMouseX = function(e) {
        e = ev.resolve(e);
        if (defined(e.pageX)) {
            return e.pageX;
        }
        if (defined(e.clientX)) {
            return e.clientX+Screen.getScrollLeft();
        }
        return null;
    };
    ev.getMouseY = function(e) {
        e = ev.resolve(e);
        if (defined(e.pageY)) {
            return e.pageY;
        }
        if (defined(e.clientY)) {
            return e.clientY+Screen.getScrollTop();
        }
        return null;
    };

    // Stop the event from bubbling up to parent elements.
    // Two method names map to the same function
    // --------------------------------------------------------------------
    ev.cancelBubble = function(e) {
        e = ev.resolve(e);
        if (typeof(e.stopPropagation)=="function") { e.stopPropagation(); }
        if (defined(e.cancelBubble)) { e.cancelBubble = true; }
    };
    ev.stopPropagation = ev.cancelBubble;

    // Prevent the default handling of the event to occur
    // --------------------------------------------------------------------
    ev.preventDefault = function(e) {
        e = ev.resolve(e);
        if (typeof(e.preventDefault)=="function") { e.preventDefault(); }
        if (defined(e.returnValue)) { e.returnValue = false; }
    };

    return ev;
})();

/* ******************************************************************* */
/*   SCREEN FUNCTIONS                                                  */
/* ******************************************************************* */
var Screen = (function() {
    var screen = {};

    // Get a reference to the body
    // --------------------------------------------------------------------
    screen.getBody = function() {
        if (document.body) {
            return document.body;
        }
        if (document.getElementsByTagName) {
            var bodies = document.getElementsByTagName("BODY");
            if (bodies!=null && bodies.length>0) {
                return bodies[0];
            }
        }
        return null;
    };

    // Get the amount that the main document has scrolled from top
    // --------------------------------------------------------------------
    screen.getScrollTop = function() {
        if (document.documentElement && defined(document.documentElement.scrollTop) && document.documentElement.scrollTop>0) {
            return document.documentElement.scrollTop;
        }
        if (document.body && defined(document.body.scrollTop)) {
            return document.body.scrollTop;
        }
        return null;
    };

    // Get the amount that the main document has scrolled from left
    // --------------------------------------------------------------------
    screen.getScrollLeft = function() {
        if (document.documentElement && defined(document.documentElement.scrollLeft) && document.documentElement.scrollLeft>0) {
            return document.documentElement.scrollLeft;
        }
        if (document.body && defined(document.body.scrollLeft)) {
            return document.body.scrollLeft;
        }
        return null;
    };

    // Util function to default a bad number to 0
    // --------------------------------------------------------------------
    screen.zero = function(n) {
        return (!defined(n) || isNaN(n))?0:n;
    };

    // Get the width of the entire document
    // --------------------------------------------------------------------
    screen.getDocumentWidth = function() {
        var width = 0;
        var body = screen.getBody();
        if (document.documentElement && (!document.compatMode || document.compatMode=="CSS1Compat")) {
            var rightMargin = parseInt(CSS.get(body,'marginRight'),10) || 0;
            var leftMargin = parseInt(CSS.get(body,'marginLeft'), 10) || 0;
            width = Math.max(body.offsetWidth + leftMargin + rightMargin, document.documentElement.clientWidth);
        }
        else {
            width =  Math.max(body.clientWidth, body.scrollWidth);
        }
        if (isNaN(width) || width==0) {
            width = screen.zero(self.innerWidth);
        }
        return width;
    };

    // Get the height of the entire document
    // --------------------------------------------------------------------
    screen.getDocumentHeight = function() {
        var body = screen.getBody();
        var innerHeight = (defined(self.innerHeight)&&!isNaN(self.innerHeight))?self.innerHeight:0;
        if (document.documentElement && (!document.compatMode || document.compatMode=="CSS1Compat")) {
            var topMargin = parseInt(CSS.get(body,'marginTop'),10) || 0;
            var bottomMargin = parseInt(CSS.get(body,'marginBottom'), 10) || 0;
            return Math.max(body.offsetHeight + topMargin + bottomMargin, document.documentElement.clientHeight, document.documentElement.scrollHeight, screen.zero(self.innerHeight));
        }
        return Math.max(body.scrollHeight, body.clientHeight, screen.zero(self.innerHeight));
    };

    // Get the width of the viewport (viewable area) in the browser window
    // --------------------------------------------------------------------
    screen.getViewportWidth = function() {
        if (document.documentElement && (!document.compatMode || document.compatMode=="CSS1Compat")) {
            return document.documentElement.clientWidth;
        }
        else if (document.compatMode && document.body) {
            return document.body.clientWidth;
        }
        return screen.zero(self.innerWidth);
    };

    // Get the height of the viewport (viewable area) in the browser window
    // --------------------------------------------------------------------
    screen.getViewportHeight = function() {
        if (!window.opera && document.documentElement && (!document.compatMode || document.compatMode=="CSS1Compat")) {
            return document.documentElement.clientHeight;
        }
        else if (document.compatMode && !window.opera && document.body) {
            return document.body.clientHeight;
        }
        return screen.zero(self.innerHeight);
    };

    return screen;
})();var Sort = (function(){
    var sort = {};
    sort.AlphaNumeric = function(a,b) {
        if (a==b) { return 0; }
        if (a<b) { return -1; }
        return 1;
    };

    sort.Default = sort.AlphaNumeric;

    sort.NumericConversion = function(val) {
        if (typeof(val)!="number") {
            if (typeof(val)=="string") {
                val = parseFloat(val.replace(/,/g,''));
                if (isNaN(val) || val==null) { val=0; }
            }
            else {
                val = 0;
            }
        }
        return val;
    };

    sort.Numeric = function(a,b) {
        return sort.NumericConversion(a)-sort.NumericConversion(b);
    };

    sort.IgnoreCaseConversion = function(val) {
        if (val==null) { val=""; }
        return (""+val).toLowerCase();
    };

    sort.IgnoreCase = function(a,b) {
        return sort.AlphaNumeric(sort.IgnoreCaseConversion(a),sort.IgnoreCaseConversion(b));
    };

    sort.CurrencyConversion = function(val) {
        if (typeof(val)=="string") {
            val = val.replace(/^[^\d\.]/,'');
        }
        return sort.NumericConversion(val);
    };

    sort.Currency = function(a,b) {
        return sort.Numeric(sort.CurrencyConversion(a),sort.CurrencyConversion(b));
    };

    sort.DateConversion = function(val) {
        // inner util function to parse date formats
        function getdate(str) {
            // inner util function to convert 2-digit years to 4
            function fixYear(yr) {
                yr = +yr;
                if (yr<50) { yr += 2000; }
                else if (yr<100) { yr += 1900; }
                return yr;
            }
            var ret;
            // YYYY-MM-DD
            if (ret=str.match(/(\d{2,4})-(\d{1,2})-(\d{1,2})/)) {
                return (fixYear(ret[1])*10000) + (ret[2]*100) + (+ret[3]);
            }
            // MM/DD/YY[YY] or MM-DD-YY[YY]
            if (ret=str.match(/(\d{1,2})[\/-](\d{1,2})[\/-](\d{2,4})/)) {
                return (fixYear(ret[3])*10000) + (ret[1]*100) + (+ret[2]);
            }
            return 99999999; // So non-parsed dates will be last, not first
        }
        return getdate(val);
    };

    sort.Date = function(a,b) {
        return sort.Numeric(sort.DateConversion(a),sort.DateConversion(b));
    };

    return sort;
})();