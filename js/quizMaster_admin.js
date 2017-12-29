jQuery(document).ready(function ($) {

	// do init
	initTabs();
	initAnswerHandler()

	/*
	 * Answer Data
	 */
	function initAnswerHandler() {

		var questionSettings = $('.qm-tab-qmqe_settings')
		if( !questionSettings.length ) {
			return // not in question editor
		}

		var answerMultiple = $('qmqe_multiple_choice_answer')
		var answerMultipleCorrect = $('qmqe_single_choice_correct')

		

	}



	// init
	function initTabs() {
		var activeTab = $('.qm-tabs li.active');

		if( !activeTab.length ) {
			activeTab = $('.qm-tabs li:first-child').addClass('active')
		}

		openTab( activeTab.data('key') )

	}

	// tab click
	$('.qm-tabs li').click( function() {

		// make tab active
		$('.qm-tabs li').removeClass('active');
		$(this).addClass('active');

		// show matching fields
		var tabKey = $(this).data('key');
		openTab( tabKey )

	});

	// open tab
	function openTab( tabKey ) {

		console.log('openTab')

		$('.qm-field-wrap').hide();
		$('.qm-field-wrap[data-tab=' + tabKey + ']').show();

		var tabFields = $('.qm-field-wrap[data-tab=' + tabKey + ']');

	}

});
