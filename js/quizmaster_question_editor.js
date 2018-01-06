jQuery(document).ready(function( $ ) {

	// get the correct repeater (or none) based on answer type
	var answerType = $('.qm-field-qmqe_answer_type input:checked').val();

	console.log(answerType)

	var repeater = false
	switch( answerType ) {
		case 'single':
			repeater = $('.qm-field-qmqe_single_choice_answers .repeater').repeater()
			break;
		case 'multiple':
			repeater = $('.qm-field-qmqe_multiple_choice_answers .repeater').repeater()
			break;
		case 'sort_answer':
			repeater = $('.qm-field-qmqe_sorting_choice_answers .repeater').repeater()
			break;
	}

	console.log(repeater);

	if( repeater ) {
		var answerDataJson = $('.qm-field-qmqe_answer_data input').val()

		var answerData = $.parseJSON( answerDataJson )
		console.log( answerData );


		repeater.setList( answerData );

	}

});
