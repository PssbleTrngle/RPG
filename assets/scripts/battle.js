$(window).ready(function() {
		
	$('.participant').click(function() {
		
		if(window.battle_action) {
			window.params.target = $(this).attr('id');		
			sendAction(window.battle_action);
		}
		
	});
	
	$('.participant').hover(function() {
		
		$('.participant').removeClass('selected');
		
		let skill = window.params.skill;
		let group = skill && $('[data-skill=' + skill + ']').attr('data-group') != 0;
		
		if(window.battle_action) {
			if(group)
				$(this).closest('.participants').find('.participant').addClass('selected');
			else 
				$(this).addClass('selected');
		}
	});
	
	$('.skill').click(function() {

		window.params.skill = $(this).attr('data-skill');
		window.battle_action = 'battle/skill';

	});	
	
});