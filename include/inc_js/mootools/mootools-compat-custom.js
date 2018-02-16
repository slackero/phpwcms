/*
MooTools 1.2 Custom Backwards-Compatibility Library
By David Isaacson
Portions from Mootools 1.2 by the MooTools production team (http://mootools.net/developers/)
Copyright (c) 2006-2007 Valerio Proietti (http://mad4milk.net/)
Copyright (c) 2008 Siafoo.net

Load after Mootools Core and More and both compatibility files
*/

// This is the definition from Mootools 1.2, with error handling
// to prevent an issue in IE where calling .item on an XML (non-HTML)
// element raises an error.
//
// We're using the 1.2 version in the first place because the compat version throws *other* weird errors sometimes
// Note that this will prevent you from using the $A(iterable, start, length) syntax allowed but undocumented (?) in Mootools 1.1 
function $A(iterable){
    var item
    try{
        item = iterable.item
    }
    catch(e){
        item = true
    }
    
    if (item){
        var array = [];
        for (var i = 0, l = iterable.length; i < l; i++) array[i] = iterable[i];
        return array;
    }
    return Array.prototype.slice.call(iterable);
}


function $extend(original, extended){
    if (!extended) {extended=original; original=this;}  // This line added
    for (var key in (extended || {})) original[key] = extended[key];
    return original;
}

Drag.Base = Drag

Element.implement({

    getValue: function(){
        return this.get('value')
    },
    
    // Very slightly modified from mootools
    toQueryString: function(){
        var queryString = [];
        this.getElements('input, select, textarea').each(function(el){
            if (!el.name || el.disabled) return;
            var value = (el.tagName.toLowerCase() == 'select') ? Element.getSelected(el).map(function(opt){
                return opt.value;
            }) : ((el.type == 'radio' || el.type == 'checkbox') && !el.checked) ? null : el.value;
            $splat(value).each(function(val){
                /*if (val)*/ queryString.push(el.name + '=' + encodeURIComponent(val));
            });
        });
        return queryString.join('&');
    }
})

Elements.implement({
    // I would actually consider this a bug
    // Also I'm sure there's a more consistant way than this to implement it
    empty: function(){
        this.each(function(element){
            element.empty()
        })
    },
    
    remove: function(){
        this.each(function(element){
            element.remove()
        })
    }
})

// Class.prototype.extend in the compat library doesn't actually work for some reason. So...
// Note that this will hide Function.extend from use in certain cases
Function.prototype.extend = function(properties){
    if (this.prototype){
        // Assume its a class
        properties.Extends = this;
        return new Class(properties);
    }        
    for (var property in properties) this[property] = properties[property];
    return this;
}

Hash.implement({
    remove: function(key){
        return this.erase(key)
    }
})

Hash.Cookie.implement({
    
    remove: function(key){
        var value = this.hash.erase(key)
        if (this.options.autoSave) this.save();
        return value
    }
})


// Completely broken in mootools-core-compat.js
XHR.implement({
    
    initialize: function(options){
        this.parent(options)
        this.transport = this.xhr
    }
})

var Ajax = new Class({
    Extends: XHR,
    
    initialize: function(url, options){
        this.url = url
        this.parent(options)
    },
    
    success: function(text, xml){
        // This version processes scripts *after* the update element is updated, like Mootools 1.1's Ajax class
        // Partially from Mootools 1.2 Remote.processScripts()
        stripped = text.stripScripts(this.options.evalScripts)
        if (this.options.update) $(this.options.update).empty().set('html', stripped);
        text = this.processScripts(text);
        this.onSuccess(text, xml);
    }
    
})

/* For further information, read http://www.siafoo.net/article/62 */
