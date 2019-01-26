// Tooltips for phpwcms Backend
window.addEvent('domready', function(){
    var as = [];
    $$('input').each(function(a){
        if(a.getAttribute('title')) {
            as.push(a);
        }
    });
    new Tips(as, {
        maxTitleChars: 25,
        offsets: {'x': 2, 'y': 5}
    });
});