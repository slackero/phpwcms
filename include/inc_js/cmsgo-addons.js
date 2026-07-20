// Loads the correct sidebar on window load,
// Collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size

let topOffset = 95;
let height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;

$(function () {
    $('#button-menu').on('click', function (e) {
        e.preventDefault();
        $('#column-left').toggleClass('active');
    });

    $(document).on('click', '.confirm-link', function (e) {
        e.preventDefault();
        const $this = $(this);
        const message = $this.attr('data-confirm') || 'Are you sure?';
        const action = $this.attr('data-confirm-action');
        const confirmType = $this.attr('data-confirm-type') || 'info';
        const href = $this.attr('href');
        bsConfirm(confirmType, message, function () {
            window.location.href = href;
        }, action);
    });

    // Intercept native confirm calls in inline onclick attributes dynamically
    $(document).on('click', 'a[onclick*="confirm("], button[onclick*="confirm("], input[type="submit"][onclick*="confirm("], input[type="button"][onclick*="confirm("]', function(e) {
        const $el = $(this);
        const onclickStr = $el.attr('onclick');
        if (!onclickStr) return;

        const match = onclickStr.match(/confirm\(\s*(['"])(.*?)\1\s*\)/);
        if (match) {
            e.preventDefault();
            e.stopImmediatePropagation();

            const confirmMsg = match[2];
            const action = $el.attr('data-confirm-action');
            const confirmType = $el.attr('data-confirm-type') || 'info';
            bsConfirm(confirmType, confirmMsg, function() {
                if ($el.is('a')) {
                    const href = $el.attr('href');
                    if (href && href !== '#') {
                        const target = $el.attr('target');
                        if (target && target !== '_self') {
                            window.open(href, target);
                        } else {
                            window.location.href = href;
                        }
                    }
                } else if ($el.is('input[type="submit"], button[type="submit"]')) {
                    const name = $el.attr('name');
                    const form = $el.closest('form');
                    if (form.length) {
                        if (name) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: name,
                                value: $el.val() || '1'
                            }).appendTo(form);
                        }
                        form.submit();
                    }
                } else {
                    $el.attr('onclick', onclickStr.replace(/return\s+confirm\(.*?\);?/, ''));
                    $el.click();
                    $el.attr('onclick', onclickStr);
                }
            }, action, confirmType);
        }
    });

    $(window).bind("load resize", function () {
        topOffset = 95;
        let width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - topOffset;
        if (height < 1) {
            height = 1;
        }
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });



    $('[data-toggle="tooltip"]').tooltip({
        delay: {
            show: 250,
            hide: 0
        },
        container: 'body',
        sanitize: false
    });

    $('img.modalButton').on('click', function (e) {
        let $this = $(this);
        const src = $this.attr('data-src');
        const modaltitle = $this.attr('alt');

        $('.modal .modal-body').css({
            'overflow-y': 'auto',
            'min-height': $(window).height() * 0.8
        });

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
        $("#browserModal h2").html(modaltitle);
    });

    $('button.modalButton').on('click', function (e) {
        const src = $(this).attr('data-src');
        $('.modal .modal-body').css({
            'overflow-y': 'auto',
            'min-height': $(window).height() * 0.8
        });

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
    });

    $('input.modalButton').on('click', function (e) {
        const src = $(this).attr('data-src');
        //var height = $(this).attr('data-height') || 300;
        //var width = $(this).attr('data-width') || 400;
        //var modaltitle = $(this).attr('data-title');

        $('.modal .modal-body').css({
            'overflow-y': 'auto',
            'min-height': $(window).height() * 0.8
        });

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
        //$("#browserModal h2").html(modaltitle);
    });

    //ajaxfunction
    $('[id^="abtn"]').on('click', function (e) {
        let $this = $(this);
        const type = $this.attr('data-type');
        const table = $this.attr('data-table');
        const field = $this.attr('data-field');
        const fieldid = $this.attr('data-fieldid');
        const id = $this.attr('data-id');
        const thisbtn = '#abtn' + type + $this.attr('data-id');

        $.ajax({
            url: 'include/inc_act/ajax_changer.php?' + CSRF_GET_TOKEN,
            xhrFields: {
                withCredentials: true
            },
            data: {
                'table': table,
                'id': id,
                'field': field,
                'fieldid': fieldid,
            },
            success: function (data) {
                if ($(thisbtn).hasClass('btn-success')) {
                    $(thisbtn).removeClass('btn-success').addClass('btn-danger');
                } else {
                    $(thisbtn).removeClass('btn-danger').addClass('btn-success');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });
    });

    $('[id^="imgpos"]').on('click', function () {
        const id = $(this).attr('id');
        const x = id.match(/[\d\.]+/g);
        $("#cimage_pos").val(x);
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $("#imgpos" + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $("#imgpos" + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#cimage_pos').on('change', function () {
        const x = $(this).val();
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $("#imgpos" + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $("#imgpos" + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $("#sendnewsletter").submit(function (event) {
        $("#messagesend").hide();
        $("#sendjobnow").show();
    });

});

function SendData1(sVar1, sVar2, sVar3, stoken) {
    // get the values
    let sVar4;
    let sVaricon;
    if (sVar1 == 4 || sVar1 == 6) {
        sVar4 = 'public';
        sVaricon = 'fa-lock';
    } else {
        sVar4 = 'visible';
        sVaricon = 'fa-eye';
    }
    let sImage = sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3;

    let sVar5 = 3;
    let sLen;
    if ($('#' + sImage).is('i')) {
        if ($('#' + sImage).hasClass('icolor1')) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    } else {
        sLen = document.getElementById(sImage).src.length;
        sVar5 = document.getElementById(sImage).src.substring(sLen - 5, sLen - 4);
        if (sVar5 == 1) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    }

    //Change image
    $.ajax({
        url: 'include/inc_act/act_articlecontent.php?' + stoken + '&do=' + sVar1 + ',' + sVar2 + ',' + sVar3 + ',' + sVar5,
        xhrFields: {
            withCredentials: true
        },
        context: document.body
    }).done(function () {
        $("#" + sImage).replaceWith('<i class="fa ' + sVaricon + ' icolor' + sVar5 + '" id="' + sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3 + '" aria-hidden="true" onclick="SendData(' + "'" + sVar1 + "','" + sVar2 + "','" + sVar3 + "','" + stoken + "'" + ')"></i>');
        //document.getElementById(sImage).src='img/button/' + sVar4 + sVar6 + sVar5 + '.gif';
    });

}

//Ajax Sort contentpart
function SendDataSort(sVar) {
    const url = 'include/inc_act/act_articlesort.php?' + CSRF_GET_TOKEN + '&sortid=' + sVar;
    $.ajax({
        url: url,
        xhrFields: {
            withCredentials: true
        }
    });
}

//set min height of wrapper if changing sidebar
$(function () {
    $('#side-menu li').on('click', function () {
        $('#side-menu ul').css("display", "none");
        $(this).children('ul').css("display", "block");
        height = height - topOffset;
        let newheight = $('#side-menu').height() + (topOffset * 2);
        if (height < newheight) {
            $("#page-wrapper").css("min-height", (newheight) + "px");
        }
    });
});
