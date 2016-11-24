$(function() {

    var accessDialog = $('#access_dialog'),
        accessSave = $('#access_save'),
        hasAgreed = function() {
            accessDialog.css('display', 'none');
            accessSave.css('display', '');
        };

    if(Cookie.get('phpwcmsAgree') === '1') {

        hasAgreed();

    } else {

        var overlay = $('<div id="accessOverlay"></div>');
        $('body').append(overlay);

        accessDialog.addClass('accessDialog');
        accessSave.css('display', '');

        overlay.css({
            'top': 0,
            'height': window.getScrollHeight(),
            'opacity': 0.75
        });

        $(window).on('resize', function(){
            overlay.css({
                'top': 0,
                'height': window.getScrollHeight()
            });
        });

        var agreeCheckbox = $('#access_agree'),
            agreeRel = 0;

        $('#agree_button_reject').click(function(){
            agreeRel = 1;
        });
        $('#agree_button_agree').click(function(){
            agreeRel = 2;
        });

        $('#access_form').submit(function(e) {

            e.preventDefault();

            if(agreeRel === 1) {

                Cookie.remove('phpwcmsAgree');
                document.location.href = redirect;

            } else if(agreeRel === 2 && agreeCheckbox.not(':checked')) {

                alert(erroralert);

            } else {

                Cookie.set('phpwcmsAgree', '1', {expires: 7, path: '/'});
                $(window).off('resize');
                hasAgreed();
                overlay.remove();

            }

        });

    }

});


