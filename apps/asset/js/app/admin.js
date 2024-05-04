$(document).ready(function () {

var menu = parseInt($('#menu').val()),
	parentMenu = [1, 3, 6, 10],
	menuCode = [0, 1, 1, 3, 3, 3, 6, 7, 7, 6, 10];

$('#app-menu').find('li').removeClass('active');
if (parentMenu.indexOf(menuCode[menu]) >= 0) {
	$('li[menu-id=parent]').addClass('nav-active active');
	$('li[menu-id=parent]').find('ul').show();
} else {
	$('li[menu-id=parent]').removeClass('nav-active active');
	$('li[menu-id=parent]').find('ul').hide();
}
$('li[menu-id=' + menuCode[menu] + ']').addClass('active');

});


function showErrorMessage(msg)
{
	$('#modal-message').load('view/partial/error_message.php', function() {
		$('.panel-body', this).html(msg);
	});

	$('#modal-message').modal('show');
}

function showSuccessMessage(msg)
{
	$('#modal-message').load('view/partial/success_message.php', function() {
		$('.panel-body', this).html(msg);
	});

	$('#modal-message').modal('show');
}