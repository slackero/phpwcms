//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size

topOffset = 95;
height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;

$(function () {

    $('#button-menu').on('click', function (e) {
        e.preventDefault();
        $('#column-left').toggleClass('active');
    });

    $(window).bind("load resize", function () {
        topOffset = 95;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) {
            height = 1;
        }
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    autosize($('textarea'));

    $('[data-toggle="tooltip"]').tooltip({
        delay: {
            show: 250,
            hide: 0
        },
        sanitize: false
    });

    $('img.modalButton').on('click', function (e) {
        var src = $(this).attr('data-src');
        var height = $(this).attr('data-height') || 300;
        var width = $(this).attr('data-width') || 400;
        var modaltitle = $(this).attr('alt');

        $('.modal .modal-body').css('overflow-y', 'auto');
        $('.modal .modal-body').css('min-height', $(window).height() * 0.8);

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
        $("#browserModal h2").html(modaltitle);
    });

    $('button.modalButton').on('click', function (e) {
        var src = $(this).attr('data-src');
        $('.modal .modal-body').css('overflow-y', 'auto');
        $('.modal .modal-body').css('min-height', $(window).height() * 0.8);

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
    });

    $('input.modalButton').on('click', function (e) {
        var src = $(this).attr('data-src');
        //var height = $(this).attr('data-height') || 300;
        //var width = $(this).attr('data-width') || 400;
        //var modaltitle = $(this).attr('data-title');

        $('.modal .modal-body').css('overflow-y', 'auto');
        $('.modal .modal-body').css('min-height', $(window).height() * 0.8);

        $("#browserModal iframe").attr({'src': src, 'height': '100%', 'width': '100%'});
        //$("#browserModal h2").html(modaltitle);
    });

    //ajaxfunction
    $('[id^="abtn"]').on('click', function (e) {
        var type = $(this).attr('data-type');
        var table = $(this).attr('data-table');
        var field = $(this).attr('data-field');
        var fieldid = $(this).attr('data-fieldid');
        var id = $(this).attr('data-id');

        var thisbtn = "#abtn" + type + $(this).attr('data-id');
        var url = 'include/inc_act/ajax_changer.php';

        $.ajax({
            url: url,
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
                alert(xhr.status);
                alert(thrownError);
            }
        });
    });

    $('[id^="imgpos"]').click(function () {
        var id = $(this).attr('id');
        var x = id.match(/[\d\.]+/g);
        $("#cimage_pos").val(x);
        for (var i = 0; i <= 9; i++) {
            if (i == x) {
                $("#imgpos" + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $("#imgpos" + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#cimage_pos').change(function () {
        var x = $(this).val();
        for (var i = 0; i <= 9; i++) {
            if (i == x) {
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
    //get the values
    if (sVar1 == 4 || sVar1 == 6) {
        sVar4 = 'public';
        sVaricon = 'fa-lock';
    } else {
        sVar4 = 'visible';
        sVaricon = 'fa-eye';
    }
    sImage = sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3;

    var sVar5 = 3;
    if ($("#" + sImage).is("i")) {
        if ($('#' + sImage).hasClass('icolor1')) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    } else {
        sLen = document.getElementById(sImage).src.length
        sVar5 = document.getElementById(sImage).src.substring(sLen - 5, sLen - 4);
        if (sVar5 == 1) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    }
    //build url
    var url = 'include/inc_act/act_articlecontent.php?' + stoken + '&do=' + sVar1 + ',' + sVar2 + ',' + sVar3 + ',' + sVar5;

    //Change image
    $.ajax({
        url: url,
        context: document.body
    }).done(function () {
        $("#" + sImage).replaceWith('<i class="fa ' + sVaricon + ' icolor' + sVar5 + '" id="' + sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3 + '" aria-hidden="true" onclick="SendData(' + "'" + sVar1 + "','" + sVar2 + "','" + sVar3 + "','" + stoken + "'" + ')"></i>');
        //document.getElementById(sImage).src='img/button/' + sVar4 + sVar6 + sVar5 + '.gif';
    });

}

//Ajax Sort contentpart
function SendDataSort(sVar) {
    var url = 'include/inc_act/act_articlesort.php?sortid=' + sVar;
    $.ajax({url: url});
}

//set min height of wrapper if changing sidebar
if (typeof jQuery == 'undefined') { // still mootools
    window.addEvent('domready', function () {
        $$('#side-menu li').addEvent('click', function (e) {
            $$('#side-menu ul').setStyle('display', 'none');
            if (this.getElements('ul').getStyle('display') == 'block') {
                this.getElements('ul').setStyle('display', 'none');
            } else {
                this.getElements('ul').setStyle('display', 'block');
            }
            height = height - topOffset;
            newheight = document.getElementById('side-menu').offsetHeight + (topOffset * 2);
            $$('#page-wrapper').setStyle('min-height', newheight);
        });
    });
} else {
   $(function () {
        $('#side-menu li').click(function () {
            $('#side-menu ul').css("display", "none");
            $(this).children('ul').css("display", "block");
            height = height - topOffset;
            newheight = $('#side-menu').height() + (topOffset * 2);
            if (height < newheight) {
                $("#page-wrapper").css("min-height", (newheight) + "px");
            }
        });
    });
}
