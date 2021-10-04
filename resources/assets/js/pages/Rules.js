$.on('/rules', function() {
	$(function() {
		$('.game-content.faq #rules .faq .heading').click(function(e){
			e.preventDefault(); 
			if($(this).parent().hasClass('opened')) {
				$(this).parent().removeClass('opened').css({'max-height':'45px'});
			} else {
				$('.game-content.faq .faq-items .faq.opened').removeClass('opened').css({'max-height':'45px'});
				$(this).parent().addClass('opened').css({'max-height':$(this).parent()[0].scrollHeight})
			}
		});
	});
}, []);