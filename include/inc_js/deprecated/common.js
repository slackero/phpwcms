$(function () {
	$('[data-toggle="tooltip"]').tooltip();
})

jQuery(document).ready(function($){
    $('#button-menu').on('click', function(e) {
        e.preventDefault();
        $('#column-left').toggleClass('active');
    });
});