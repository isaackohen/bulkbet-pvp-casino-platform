$.on('/history', function() {
	$('.room-selector .room:first').addClass('active');
	$('.content .historyTable:first').addClass('active');
	$('.room-selector .room').click(function (e) {
	if (!$(this).is('.active')) {
		$('.room-selector .room, .historyTable').removeClass('active');
		$(this).addClass('active');
		$('.historyTable:eq(' + $(this).index() + ')').addClass('active');
	}
	});
}, []);