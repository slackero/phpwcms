(function(){var a=function(j,e,c,i,b){var h=(j/10).toInt();var g=j%10;var f=(j/100).toInt();if(h==1&&j>10){return i}if(g==1){return e}if(g>0&&g<5){return c}return i};MooTools.lang.set("uk-UA","Date",{months:["Січень","Лютий","Березень","Квітень","Травень","Червень","Липень","Серпень","Вересень","Жовтень","Листопад","Грудень"],days:["Неділя","Понеділок","Вівторок","Середа","Четвер","П'ятниця","Субота"],dateOrder:["date","month","year"],AM:"до полудня",PM:"по полудню",shortDate:"%d/%m/%Y",shortTime:"%H:%M",ordinal:"",lessThanMinuteAgo:"меньше хвилини тому",minuteAgo:"хвилину тому",minutesAgo:function(b){return"{delta} "+a(b,"хвилину","хвилини","хвилин")+" тому"},hourAgo:"годину тому",hoursAgo:function(b){return"{delta} "+a(b,"годину","години","годин")+" тому"},dayAgo:"вчора",daysAgo:function(b){return"{delta} "+a(b,"день","дня","днів")+" тому"},weekAgo:"тиждень тому",weeksAgo:function(b){return"{delta} "+a(b,"тиждень","тижні","тижнів")+" тому"},monthAgo:"місяць тому",monthsAgo:function(b){return"{delta} "+a(b,"місяць","місяці","місяців")+" тому"},yearAgo:"рік тому",yearsAgo:function(b){return"{delta} "+a(b,"рік","роки","років")+" тому"},lessThanMinuteUntil:"за мить",minuteUntil:"через хвилину",minutesUntil:function(b){return"через {delta} "+a(b,"хвилину","хвилини","хвилин")},hourUntil:"через годину",hoursUntil:function(b){return"через {delta} "+a(b,"годину","години","годин")},dayUntil:"завтра",daysUntil:function(b){return"через {delta} "+a(b,"день","дня","днів")},weekUntil:"через тиждень",weeksUntil:function(b){return"через {delta} "+a(b,"тиждень","тижні","тижнів")},monthUntil:"через місяць",monthesUntil:function(b){return"через {delta} "+a(b,"місяць","місяці","місяців")},yearUntil:"через рік",yearsUntil:function(b){return"через {delta} "+a(b,"рік","роки","років")}})})();