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

		// init answer type fields
		var answerType = $('.qm-field-qmqe_answer_type input:checked').val()

		qmAnswerFieldsetHideAll()
		qmAnswerFieldsetShow( answerType )

		// attach click handler
		$('.qm-field-qmqe_answer_type li input').on( 'click.selection', function() {

			var answerType = $( this ).val()
			qmAnswerFieldsetHideAll()
			qmAnswerFieldsetShow( answerType )

		})

		// open tab event
		$( document ).on( 'quizmasterOpenTab', function( e ) {

			if( e.tabKey == 'answers' ) {

				var answerType = $('.qm-field-qmqe_answer_type input:checked').val()

				console.log(answerType);

				qmAnswerFieldsetHideAll()
				qmAnswerFieldsetShow( answerType )

			}
		})

	}

	function qmAnswerFieldsetShow( answerType ) {
		var fields = qmAnswerFieldsets()
		switch( answerType ) {
			case 'single':
				fields.single.show()
				break;
			case 'multiple':
				fields.multiple.show()
				break;
			case 'sort_answer':
				fields.sorting.show()
				break;
			case 'free_answer':
				fields.free.show()
				break;
			case 'fill_blank':
				fields.fillBlank.show()
				break;
		}
	}

	function qmAnswerFieldsetHideAll() {
		var fields = qmAnswerFieldsets()
		fields.single.hide()
		fields.multiple.hide()
		fields.sorting.hide()
		fields.free.hide()
		fields.fillBlank.hide()
	}

	function qmAnswerFieldsets() {
		return {
			single: $('.qm-field-qmqe_single_choice_answers'),
			multiple: $('.qm-field-qmqe_multiple_choice_answers'),
			sorting: $('.qm-field-qmqe_sorting_choice_answers'),
			free: $('.qm-field-qmqe_free_choice_answers'),
			fillBlank: $('.qm-field-qmqe_fill_blanks'),
		}
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

		console.log('openTab: ' + tabKey)

		$('.qm-tab').hide()
		var tabClass = '.qm-tab-' + tabKey
		$( tabClass ).show();

		var tabFields = $('.qm-field-wrap[data-tab=' + tabKey + ']');

		$( document ).trigger({
			type: 'quizmasterOpenTab',
			tabKey: tabKey
		})

	}

	/* Repeater */
	$('.repeater').repeater({
    initEmpty: false,
    defaultValues: {
      'text-input': 'foo'
    },
    ready: function (setIndexes) {
        // $dragAndDrop.on('drop', setIndexes);
    },
		show: function () {
      $(this).show();
    },
    isFirstItemUndeletable: true
  })

});
