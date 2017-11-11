jQuery(document).ready(function( $ ) {

	// question type selected
	var qType = $('input[name=field_5885e9f669c6f]:checked').val()

	qmShowAnswerFields( qType )




	// show the relevant answer fields based on the question type selected
	function qmShowAnswerFields( qType ) {

		// hide all fields
		var fields = $('.qm-tab-answers .qm-field-wrap');
		$('.qm-tab-answers .qm-field-wrap').hide();

		console.log(qType)

		switch( qType ) {

			case 'single':
				var answerFieldParent = $('.qm-field-qmqe_single_choice_answers');
				answerFieldParent.show();
				answerFieldParent.children('.qm-field-wrap').show()
				break;
			case 'multiple':
				var answerFieldParent = $('.qm-field-qmqe_multiple_choice_answer');
				answerFieldParent.show();
				answerFieldParent.children('.qm-field-wrap').show()
				break;
			case 'free_answer':
				var answerFieldParent = $('.qm-field-qmqe_multiple_choice_answer');
				answerFieldParent.show();
				answerFieldParent.children('.qm-field-wrap').show()
				break;
			case 'sort_answer':
				var answerFieldParent = $('.qm-field-qmqe_multiple_choice_answer');
				answerFieldParent.show();
				answerFieldParent.children('.qm-field-wrap').show()
				break;
			case 'fill_blank':
				var answerFieldParent = $('.qm-field-qmqe_multiple_choice_answer');
				answerFieldParent.show();
				answerFieldParent.children('.qm-field-wrap').show()
				break;

		}

	}


	// load new selection
	// only allow 1 selection for single choice correct









});
