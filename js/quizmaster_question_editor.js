jQuery(document).ready(function( $ ) {

	// get the correct repeater (or none) based on answer type
	var answerType = $('.qm-field-qmqe_answer_type input:checked').val();

	// repeater settings
	var repeaterSettings = {
		initEmpty: false,
		defaultValues: {

		},
		ready: function (setIndexes) {
				// $dragAndDrop.on('drop', setIndexes);
		},
		show: function () {
			$(this).show();
		},
		isFirstItemUndeletable: false
	}

	// init repeaters
	repeaterSingle = $('.qm-field-qmqe_single_choice_answers .repeater').repeater()
	repeaterMultiple = $('.qm-field-qmqe_multiple_choice_answers .repeater').repeater()
	repeaterSorting = $('.qm-field-qmqe_sorting_choice_answers .repeater').repeater()

	// set list on active repeater
	var answerDataJson = $('.qm-field-qmqe_answer_data input').val()
	var answerData = $.parseJSON( answerDataJson )
	switch( answerType ) {
		case 'single':
			repeaterSingle.setList( answerData );
			break;
		case 'multiple':
			repeaterMultiple.setList( answerData );
			break;
		case 'sort_answer':
			repeaterSorting.setList( answerData );
			break;
	}

	// add sorting
	$('body').find('[data-repeater-list]').sortable();

});
