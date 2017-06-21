jQuery(document).ready(function( $ ) {

	// QuizMaster jQuery plugin
	$.fn.quizmaster = function( options = false ) {

		var quizmaster = this;

		quizmaster.config = {},
		quizmaster.events = {};

		quizmaster.data = {
			results: new Object(),
			catResults: new Object(),
			currentQuestion: false,
			currentQuestionId: 0,
			quizSolved: [],
			lastButtonValue: "",
			inViewQuestions: false,
			currentPage: 1,
			isQuizStarted: false,
		};

		quizmaster.elements = {
			checkButtonClass: '.qm-button-check',
			nextButtonClass: '.qm-button-next',
			skipButtonClass: 'input[name="skip"]',
			singlePageLeft: 'input[name="quizMaster_pageLeft"]',
			singlePageRight: 'input[name="quizMaster_pageRight"]',
			startButton: quizmaster.find('.qm-start-button'),
			backButton: quizmaster.find('input[name="back"]'),
			nextButton: quizmaster.find('.qm-button-next'),
			skipButton: quizmaster.find('input[name="skip"]'),
			checkButton: quizmaster.find('.qm-button-check'),
			restartButtonClass: '.qm-restart-quiz-button',
			restartButton: quizmaster.find('.qm-restart-quiz-button'),
			questionReviewButton: quizmaster.find('.qm-question-review-button'),
			quiz: quizmaster.find('.quizMaster_quiz'),
			questionListClass: '.qm-question-list',
			questionList: quizmaster.find('.quizMaster_list'),
			resultsBox: quizmaster.find('.qm-results-box'),
			reviewBox: quizmaster.find('.quizMaster_reviewQuestion'),
			questionCheck: quizmaster.find('.qm-check-answer-box'),
			startPage: quizmaster.find('.qm-quiz-start-box'),
			timeLimitBox: quizmaster.find('.qm-time-limit'),
			hintTrigger: quizmaster.find('.qm-hint-trigger'),
			listItems: $()
		};

		quizmaster.startPageShow = function() {

			quizmaster.elements.startPage.show();

		};

		quizmaster.startPageHide = function() {
			quizmaster.elements.startPage.hide();
		};

		quizmaster.loadQuizData = function () {

			quizmaster.ajax({
				action: 'quizmaster_admin_ajax',
				func: 'loadQuizData',
				data: {
					quizId: quizmaster.config.quizId
				}
			}, function (json) {

					if (json.averageResult != undefined) {
						//quizmaster.setAverageResult(json.averageResult, true);
					}

			});

		};

		quizmaster.nextQuestion = function () {
			quizmaster.showQuestionObject( quizmaster.data.currentQuestion.next() );
		};

		quizmaster.prevQuestion = function () {
			quizmaster.showQuestionObject( quizmaster.data.currentQuestion.prev() );
		};

		quizmaster.getCurrentQuestion = function () {
			return quizmaster.data.currentQuestion;
		}

		quizmaster.getCurrentQuestionId = function () {
			return quizmaster.data.currentQuestionId;
		}

		quizmaster.showQuestionObject = function ( obj ) {

			if( obj == 'current' ) {
				obj = quizmaster.data.currentQuestion;
			}

				/*
				if (!obj.length && bitOptions.forcingQuestionSolve && bitOptions.quizSummeryHide && bitOptions.reviewQustion) {
					for (var i = 0, c = $('.quizMaster_listItem').length; i < c; i++) {
						if (!quizSolved[i]) {
							alert(QuizMasterGlobal.questionsNotSolved);
							return false;
						}
					}
				}
				*/

				quizmaster.data.currentQuestion.hide();
				quizmaster.data.currentQuestion = obj.show();

				// Change last name
				if( quizmaster.questionCount() == quizmaster.data.currentQuestion.index() +1 ) {
					var $lastButton = quizmaster.elements.nextButton.last();
					lastButtonValue = $lastButton.val();
					$lastButton.val(quizmaster.config.lbn);
				}

				quizmaster.scrollTo( quizmaster.elements.quiz );

				// question show event
				quizmaster.trigger({
					type: 'quizmaster.questionShow',
					question: quizmaster.data.currentQuestion,
					questionIndex: quizmaster.data.currentQuestion.index()
				});

				if ( !quizmaster.data.currentQuestion.length ) {
					quizmaster.showQuizSummary();
				} else {
					quizmaster.timer.question.start( quizmaster.getCurrentQuestionId() );
				}

		};

		/*
     * Show quiz summary
		 */
		quizmaster.showQuizSummary = function () {

				quizmaster.finishQuiz();

				var quizSummary = quizmaster.find('.qm-check-page');

				quizSummary.find('ol:eq(0)').empty()
						.append(quizmaster.find('.quizMaster_reviewQuestion ol li').clone().removeClass('quizMaster_reviewQuestionTarget'))
						.children().click(function (e) {
								quizSummary.hide();
								quizmaster.elements.quiz.show();
								reviewBox.show(true);

								quizmaster.showQuestion($(this).index());
						});

				var cSolved = 0;

				for (var i = 0, c = quizmaster.data.quizSolved.length; i < c; i++) {
						if ( quizmaster.data.quizSolved[i] ) {
								cSolved++;
						}
				}

				quizSummary.find('span:eq(0)').text(cSolved);

				quizmaster.elements.reviewBox.hide();
				quizmaster.elements.quiz.hide();

				quizSummary.show();

				quizmaster.scrollTo(quizSummary);
		};

		quizmaster.checkButtonInit = function() {

			quizmaster.elements.checkButton.click( function () {

				if (quizmaster.config.bitOptions.forcingQuestionSolve && !quizmaster.data.quizSolved[ quizmaster.data.currentQuestion.index() ]
					&& (quizmaster.config.bitOptions.quizSummeryHide || !quizmaster.config.bitOptions.reviewQustion)) {

					return false;
				}

				quizmaster.checkQuestion();

			});

		};

		quizmaster.checker = function (name, data) {
				var correct = true;
				var points = 0;
				var isDiffPoints = $.isArray(data.points);
				var statistcAnswerData = {};

				var func = {
						singleMulti: function () {
								var input = quizmaster.elements.questionList.find('.quizMaster_questionInput').attr('disabled', 'disabled');
								var isDiffMode = data.diffMode;

								quizmaster.elements.questionList.children().each(function (i) {
										var $item = $(this);
										var index = $item.data('pos');
										var checked = input.eq(i).is(':checked');

										if (data.correct[index]) {
												if (!checked) {
														correct = false;
												} else {
														if (isDiffPoints) {
																if (isDiffMode)
																		points = data.points[index];
																else
																		points += data.points[index];
														}
												}

												if (data.disCorrect) {
														correct = true;
												} else {
														quizmaster.marker($item, true);
												}

										} else {
												if (checked) {
														if (!data.disCorrect) {
																quizmaster.marker($item, false);
																correct = false;
														} else {
																correct = true;
														}

														if (isDiffMode)
																points = data.points[index];
												} else {
														if (isDiffPoints && !isDiffMode) {
																points += data.points[index];
														}
												}
										}
								});
						},

						sort_answer: function () {
								var $items = quizmaster.elements.questionList.children();

								$items.each(function (i, v) {
										var $this = $(this);

										statistcAnswerData[i] = $this.data('pos');

										if (i == $this.data('pos')) {
												quizmaster.marker($this, true);

												if (isDiffPoints) {
														points += data.points[i];
												}
										} else {
												quizmaster.marker($this, false);
												correct = false;
										}
								});

								$items.children().css({
										'box-shadow': '0 0',
										'cursor': 'auto'
								});

								quizmaster.elements.questionList.sortable("destroy");

								$items.sort(function (a, b) {
										return $(a).data('pos') > $(b).data('pos') ? 1 : -1;
								});

								quizmaster.elements.questionList.append($items);
						},

						matrix_sort_answer: function () {
								var $items = quizmaster.elements.questionList.children();
								var matrix = new Array();
								statistcAnswerData = {0: -1};

								$items.each(function () {
										var $this = $(this);
										var i = $this.data('pos');
										var $stringUl = $this.find('.quizMaster_maxtrixSortCriterion');
										var $stringItem = $stringUl.children();

										if ($stringItem.length)
												statistcAnswerData[i] = $stringItem.data('pos');

										if ($stringItem.length && $.inArray(String(i), String($stringItem.data('correct')).split(',')) >= 0) {
//						if(i == $stringItem.data('pos')) {
												quizmaster.marker($stringUl, true);

												if (isDiffPoints) {
														points += data.points[i];
												}
										} else {
												correct = false;
												quizmaster.marker($stringUl, false);
										}

										matrix[i] = $stringUl;
								});

								quizmaster.resetMatrix($question);

								$question.find('.quizMaster_sortStringItem').each(function () {
										var x = matrix[$(this).data('pos')];
										if (x != undefined)
												x.append(this);
								}).css({
										'box-shadow': '0 0',
										'cursor': 'auto'
								});

								$question.find('.quizMaster_sortStringList, .quizMaster_maxtrixSortCriterion').sortable("destroy");
						},

						free_answer: function () {
								var $li = quizmaster.elements.questionList.children();
								var value = $li.find('.quizMaster_questionInput').attr('disabled', 'disabled').val();

								if ($.inArray($.trim(value).toLowerCase(), data.correct) >= 0) {
										quizmaster.marker($li, true);
								} else {
										quizmaster.marker($li, false);
										correct = false;
								}
						},

						cloze_answer: function () {
								quizmaster.elements.questionList.find('.quizMaster_cloze').each(function (i, v) {
										var $this = $(this);
										var cloze = $this.children();
										var input = cloze.eq(0);
										var span = cloze.eq(1);
										var inputText = quizmaster.cleanupCurlyQuotes(input.val());

										if ($.inArray(inputText, data.correct[i]) >= 0) {
												if (isDiffPoints) {
														points += data.points[i];
												}

												if (!bitOptions.disabledAnswerMark) {
														input.css('background-color', '#B0DAB0');
												}
										} else {
												if (!bitOptions.disabledAnswerMark) {
														input.css('background-color', '#FFBABA');
												}

												correct = false;

												span.show();
										}

										input.attr('disabled', 'disabled');
								});
						},

						assessment_answer: function () {
								correct = true;
								var $input = quizmaster.elements.questionList.find('.quizMaster_questionInput').attr('disabled', 'disabled');
								var val = 0;

								$input.filter(':checked').each(function () {
										val += parseInt($(this).val());
								});

								points = val;
						}
				};

				func[name]();

				if (!isDiffPoints && correct) {
						points = data.points;
				}

				return {
						c: correct,
						p: points,
						s: statistcAnswerData
				};
		};

		quizmaster.marker = function (e, correct) {

			if ( !quizmaster.config.bitOptions.disabledAnswerMark ) {
				if (correct) {
					e.addClass('quizMaster_answerCorrect');
				} else {
					e.addClass('quizMaster_answerIncorrect');
				}
			}

		};


		quizmaster.checkQuestion = function( list, endCheck ) {

			// stop timer
			quizmaster.timer.question.stop();

			// check current question
			var data = quizmaster.config.json[ quizmaster.getCurrentQuestionId() ];
			var name = data.type;

			if ( quizmaster.getCurrentQuestion().data('check') ) {
				return true;
			}

			// check answer
			if (data.type == 'single' || data.type == 'multiple') {
				name = 'singleMulti';
			}

			var result = quizmaster.checker( name, data );

			// organize result from checking answer
			quizmaster.data.results[data.id].points = result.p;
			quizmaster.data.results[data.id].correct = Number(result.c);
			quizmaster.data.results[data.id].data = result.s;
			quizmaster.data.results['comp'].points += result.p;
			quizmaster.data.catResults[data.catId] += result.p;
			quizmaster.getCurrentQuestion().data('check', true);

			// end check trigger
			quizmaster.trigger({
				type: 'questionSolved',
				values: {
					item: quizmaster.data.currentQuestion,
					index: quizmaster.data.currentQuestion.index(),
					solved: true,
					fake: true
				}
			});

			// global event
			$(document).trigger({
				type: 'quizmasterQuestionSolved',
				values: {
					item: quizmaster.data.currentQuestion,
					index: quizmaster.data.currentQuestion.index(),
					questionCount: quizmaster.questionCount(),
					solved: true,
					results: quizmaster.data.results,
				}
			});

		};

		quizmaster.parseBitOptions = function () {

			if (quizmaster.config.bo) {
				quizmaster.config.bitOptions.randomAnswer = quizmaster.config.bo & (1 << 0);
				quizmaster.config.bitOptions.randomQuestion = quizmaster.config.bo & (1 << 1);
				quizmaster.config.bitOptions.disabledAnswerMark = quizmaster.config.bo & (1 << 2);
				quizmaster.config.bitOptions.checkBeforeStart = quizmaster.config.bo & (1 << 3);
				quizmaster.config.bitOptions.preview = quizmaster.config.bo & (1 << 4);
				quizmaster.config.bitOptions.isAddAutomatic = quizmaster.config.bo & (1 << 6);
				quizmaster.config.bitOptions.reviewQustion = quizmaster.config.bo & ( 1 << 7);
				quizmaster.config.bitOptions.quizSummeryHide = quizmaster.config.bo & (1 << 8);
				quizmaster.config.bitOptions.skipButton = quizmaster.config.bo & (1 << 9);
				quizmaster.config.bitOptions.autoStart = quizmaster.config.bo & (1 << 10);
				quizmaster.config.bitOptions.forcingQuestionSolve = quizmaster.config.bo & (1 << 11);
				quizmaster.config.bitOptions.hideQuestionPositionOverview = quizmaster.config.bo & (1 << 12);
				quizmaster.config.bitOptions.formActivated = quizmaster.config.bo & (1 << 13);
				quizmaster.config.bitOptions.maxShowQuestion = quizmaster.config.bo & (1 << 14);
				quizmaster.config.bitOptions.sortCategories = quizmaster.config.bo & (1 << 15);

				var cors = quizmaster.config.bo & (1 << 5);

				if (cors && jQuery.support != undefined && jQuery.support.cors != undefined && jQuery.support.cors == false) {
					quizmaster.config.bitOptions.cors = cors;
				}
			}

		};


		quizmaster.ajax = function (data, success, dataType) {
				dataType = dataType || 'json';

				if (quizmaster.config.bitOptions.cors) {
						jQuery.support.cors = true;
				}

				$.post(QuizMasterGlobal.ajaxurl, data, success, dataType);

				if (quizmaster.config.bitOptions.cors) {
						jQuery.support.cors = false;
				}
		};

		quizmaster.startButtonInit = function() {

			quizmaster.elements.startButton.click( function () {
				quizmaster.startQuiz();
			});

		};

		/*
     *
		 */
		quizmaster.nextButtonInit = function() {

			quizmaster.elements.nextButton.click(function () {

				if ( quizmaster.config.bitOptions.forcingQuestionSolve && !quizmaster.data.quizSolved[ quizmaster.getCurrentQuestion().index() ]
					&& ( quizmaster.config.bitOptions.quizSummeryHide || !quizmaster.config.bitOptions.reviewQustion )) {
					return false;
				}

				quizmaster.nextQuestion();
			});

		};

		quizmaster.startQuiz = function() {

			quizmaster.startPageHide();
			quizmaster.loadQuizData();

			quizmaster.elements.nextButton = $('.qm-button-next');
			quizmaster.elements.checkButton.show();

			var $listItem = quizmaster.elements.questionList.children();
			quizmaster.elements.listItems = $('.quizMaster_list > li');

			// start time limit
			quizmaster.timer.limit.start();

			quizmaster.data.quizSolved = [];
			quizmaster.data.results = {
				comp: {
					points: 0,
					correctQuestions: 0,
					quizTime: 0,
					answered: 0,
					skipped: 0,
					solved: 0,
				}
			};

			$('.qm-question-list').each(function () {

					var questionId = $(this).data('question_id');

					quizmaster.data.results[ questionId ] = {
						time: 0,
						solved: 0
					};

			});

			quizmaster.data.catResults = {};
			$.each( quizmaster.config.options.catPoints, function (i, v) {
				quizmaster.data.catResults[i] = 0;
			});

			quizmaster.elements.quiz.show();
			quizmaster.elements.reviewBox.show();

			// start timer
			quizmaster.timer.quiz.start();

			// get first question object and show
			var $listItem = quizmaster.elements.questionList.children();
			quizmaster.setCurrentQuestion( $listItem.eq(0) );
			quizmaster.showQuestionObject( 'current' );

		};

		quizmaster.setCurrentQuestion = function( $question ) {

			quizmaster.data.currentQuestion = $question;
			quizmaster.data.currentQuestionId = $question.find(quizmaster.elements.questionListClass).data('question_id');

		};

		quizmaster.questionCount = function () {
			return quizmaster.find('.quizMaster_listItem').length;
		};

		quizmaster.finishQuiz = function (timeover) {

			// deactivate hint trigger
			quizmaster.hintDisable();

			quizmaster.timer.question.stop();
			quizmaster.timer.quiz.stop();
			quizmaster.timer.limit.stop();

			var time = (+new Date() - quizmaster.timer.quizStartTime);
			time = (quizmaster.config.timeLimit && time > quizmaster.config.timeLimit) ? quizmaster.config.timeLimit : time;

			quizmaster.find('.quizMaster_quiz_time span').text( quizmaster.timer.parseTime(time) );

			if (timeover) {
				quizmaster.elements.resultsBox.find('.qm-time-limit_expired').show();
			}

			quizmaster.checkQuestion(quizmaster.elements.questionList.children(), true);
			quizmaster.find('.quizMaster_correct_answer').text(quizmaster.data.results.comp.correctQuestions);

			quizmaster.data.results.comp.result = Math.round(quizmaster.data.results.comp.points / quizmaster.config.globalPoints * 100 * 100) / 100;
			var $pointFields = quizmaster.find('.quizMaster_points span');

			$pointFields.eq(0).text(quizmaster.data.results.comp.points);
			$pointFields.eq(1).text(quizmaster.config.globalPoints);
			$pointFields.eq(2).text(quizmaster.data.results.comp.result + '%');

			var $resultText = quizmaster.find('.qm-results-boxList > li').eq(0);

			$resultText.find('.quizMaster_resultForm').each(function () {
				var $this = $(this);
				var formId = $this.data('form_id');
				var data = formData[formId];

				if (typeof data === 'object') {
						data = data['day'] + '-' + data['month'] + '-' + data['year'];
				}

				$this.text(data).show();
			});

			$resultText.show();

			//Result-Text END

			quizmaster.setAverageResult(quizmaster.data.results.comp.result, false);

			quizmaster.setCategoryOverview();

			quizmaster.sendCompletedQuiz();

			// hide buttons
			quizmaster.elements.checkButton.hide();
			quizmaster.elements.skipButton.hide();

			quizmaster.elements.reviewBox.hide();

			quizmaster.find('.qm-check-page, .qm-info-page').hide();
			quizmaster.elements.quiz.hide();
			quizmaster.elements.resultsBox.show();
			quizmaster.scrollTo(quizmaster.elements.resultsBox);

			/* global trigger */
			$(document).trigger({
				type: 'quizmasterQuizCompleted',
				values: {
					questionCount: quizmaster.questionCount(),
					results: quizmaster.data.results,
				}
			});

			// reset result comp
			quizmaster.data.results.comp.solved 	= 0;
			quizmaster.data.results.comp.answered = 0;
			quizmaster.data.results.comp.skipped 	= 0;

		};

		/*
     * Hint Handler Functions
		 */

		 quizmaster.hintInit = function() {

 			quizmaster.on('quizmaster.questionShow', function() {

				var $hint = quizmaster.getCurrentQuestion().find('.quizMaster_tipp')
				if( ! $hint.length ) {
					quizmaster.hintDisable();
				} else {
					quizmaster.hintEnable();
				}

 			});
 		};

		 quizmaster.hintDisable = function () {

 			$tipModal = $('.qm-hint-modal');
 			$tipModal.hide();
 			quizmaster.elements.hintTrigger.removeClass('qm-hint-enabled')
 			quizmaster.elements.hintTrigger.addClass('qm-hint-disabled')
 			quizmaster.elements.hintTrigger.off( 'click', quizmaster.hintHide )
 			quizmaster.elements.hintTrigger.off( 'click', quizmaster.hintShow )

 		};

 		quizmaster.hintEnable = function () {

 			quizmaster.elements.hintTrigger.removeClass('qm-hint-disabled')
 			quizmaster.elements.hintTrigger.addClass('qm-hint-enabled')
 			quizmaster.elements.hintTrigger.off( 'click', quizmaster.hintHide )
 			quizmaster.elements.hintTrigger.on( 'click', quizmaster.hintShow )

 		};

 		quizmaster.hintHide = function ( event ) {

 			$tipModal = $('.qm-hint-modal');
 			$tipModal.hide();
 			quizmaster.elements.hintTrigger.off( 'click', quizmaster.hintHide )
 			quizmaster.elements.hintTrigger.on( 'click', quizmaster.hintShow )

 		};

 		quizmaster.hintShow = function ( event ) {

 			var $this = $(this);
 			var id = quizmaster.getCurrentQuestionId();

 			// get tip div
 			var $hint = quizmaster.data.currentQuestion.find('.quizMaster_tipp')
 			var $tip = $hint.html();
 			$tipModal = $('.qm-hint-modal');
 			$tipModalContents = $('.qm-hint-modal .qm-hint-content');

 			// populate modal with current question tip
 			$tipModalContents.html( $tip )

 			// adjust modal position
 			$tipModal.css({
 				position: "absolute",
 				left: $this.position().left + "px",
 				top: ($this.position().top + $this.outerHeight()) + "px",
 				display: "block",
 			});

 			quizmaster.elements.hintTrigger.on( 'click', quizmaster.hintHide )
 			quizmaster.elements.hintTrigger.off( 'click', quizmaster.hintShow )

 			// record use of tip
 			quizmaster.data.results[id].tip = 1;

 		};

		/*
     * Timer Class
		 */
		quizmaster.timer = {

			questionStartTime: 0,
			quizStartTime: 0,

			limit: {

				intervalId: 0,

				stop: function () {
					if ( quizmaster.config.timeLimit ) {
						window.clearInterval( quizmaster.timer.limit.intervalId );
						quizmaster.elements.timeLimitBox.hide();
					}
				},

				start: function () {

					if (! quizmaster.config.timeLimit )
						return;

					var $timeText = quizmaster.elements.timeLimitBox.find('span').text( quizmaster.timer.parseTime( quizmaster.config.timeLimit ) );
					var $timeDiv = quizmaster.elements.timeLimitBox.find('.qm-progress-box');

					quizmaster.elements.timeLimitBox.show();

					var beforeTime = +new Date();

					quizmaster.timer.limit.intervalId = window.setInterval(function () {

						var diff = (+new Date() - beforeTime);
						var elapsedTime = (quizmaster.config.timeLimit) - diff;

						if (diff >= 500) {
							$timeText.text( quizmaster.timer.parseTime(Math.ceil(elapsedTime)) );
						}

						$timeDiv.css('width', (elapsedTime / quizmaster.config.timeLimit * 100) + '%');

						if (elapsedTime <= 0) {

							console.log('elapsed time: ' + elapsedTime)

							quizmaster.timer.limit.stop();
							quizmaster.finishQuiz( true );
						}

					});
				},

			},

			question: {

				start: function ( questionId ) {
					if ( quizmaster.data.currentQuestionId != 0 )
						quizmaster.stop();

					quizmaster.data.currentQuestionId = questionId;
					quizmaster.timer.questionStartTime = +new Date();

				},

				stop: function () {

					if ( quizmaster.getCurrentQuestionId() == 0 )
							return;

					quizmaster.data.results[ quizmaster.getCurrentQuestionId() ].time += Math.round((new Date() - quizmaster.timer.questionStartTime));

				},

			},

			quiz: {

				start: function () {
					if ( quizmaster.data.isQuizStarted )
						quizmaster.stopQuiz();

					quizmaster.timer.quizStartTime = +new Date();


					quizmaster.data.isQuizStarted = true;

					console.log( 'quizmaster.data.isQuizStarted ' + quizmaster.data.isQuizStarted )
				},

				stop: function () {

					console.log( 'quizmaster.data.isQuizStarted ' + quizmaster.data.isQuizStarted )

					if ( !quizmaster.data.isQuizStarted ) {
						return;
					}

					quizmaster.data.results['comp'].quizTime += new Date() - quizmaster.timer.quizStartTime;
					quizmaster.data.isQuizStarted = false;

				},

			},

			convertTimeLimitMs: function() {
				if( quizmaster.config.timeLimit ) {
					quizmaster.config.timeLimit = quizmaster.config.timeLimit * 1000;
				}
			},

			parseTime: function (ms) {

				console.log( 'parseTime' )
				console.log( ms )

				var seconds = parseInt(ms / 1000);
				var minutes = parseInt((seconds / 60) % 60);
				var hours = parseInt((seconds / 3600) % 24);

				seconds = (seconds > 9 ? '' : '0') + seconds;
				minutes = (minutes > 9 ? '' : '0') + minutes;
				hours = (hours > 9 ? '' : '0') + hours;

				console.log( hours + ':' + minutes + ':' + seconds )

				return hours + ':' + minutes + ':' + seconds;
			},

		};

		quizmaster.setAverageResult = function (p, g) {
			var v = quizmaster.find('.quizMaster_resultValue:eq(' + (g ? 0 : 1) + ') > * ');
			v.eq(1).text(p + '%');
			v.eq(0).css('width', (240 * p / 100) + 'px');
		};

		quizmaster.setCategoryOverview = function () {

				quizmaster.data.results.comp.cats = {};

				quizmaster.find('.quizMaster_catOverview li').each(function () {

					var $this = $(this);
					var catId = $this.data('category_id');

					if (quizmaster.config.catPoints[catId] === undefined) {
							$this.hide();
							return true;
					}

					var r = Math.round(quizmaster.data.catResults[catId] / quizmaster.config.catPoints[catId] * 100 * 100) / 100;

					quizmaster.data.results.comp.cats[catId] = r;

					$this.find('.quizMaster_catPercent').text(r + '%');

					$this.show();
				});

		};

		quizmaster.sendCompletedQuiz = function () {

			quizmaster.fetchAllAnswerData( quizmaster.data.results );

			quizmaster.ajax({
				action: 'quizmaster_admin_ajax',
				func: 'completedQuiz',
				data: {
					quizId: quizmaster.config.quizId,
					results: quizmaster.data.results,
				}
			});

		};

		quizmaster.fetchAllAnswerData = function (resultData) {
				quizmaster.find('.quizMaster_questionList').each(function () {
						var $this = $(this);
						var questionId = $this.data('question_id');
						var type = $this.data('type');
						var data = {};

						if (type == 'single' || type == 'multiple') {
								$this.find('.quizMaster_questionListItem').each(function () {
										data[$(this).data('pos')] = +$(this).find('.quizMaster_questionInput').is(':checked');
								});
						} else if (type == 'free_answer') {
								data[0] = $this.find('.quizMaster_questionInput').val();
						} else if (type == 'sort_answer') {
								return true;
						} else if (type == 'matrix_sort_answer') {
								return true;
						} else if (type == 'cloze_answer') {
								var i = 0;
								$this.find('.quizMaster_cloze input').each(function () {
										data[i++] = $(this).val();
								});
						} else if (type == 'assessment_answer') {
								data[0] = '';

								$this.find('.quizMaster_questionInput:checked').each(function () {
										data[$(this).data('index')] = $(this).val();
								});
						}

						quizmaster.data.resultData[questionId]['data'] = data;

				});
		};

		/*
     * Question Review
		 */
		quizmaster.questionReviewButtonInit = function() {

			quizmaster.elements.questionReviewButton.on( 'click', function () {
					quizmaster.showQuestionList();
			});

		};

		quizmaster.showQuestionList = function () {

				quizmaster.elements.quiz.toggle();
				quizmaster.find('.quizMaster_QuestionButton').hide();
				quizmaster.elements.questionList.children().show();
				quizmaster.elements.reviewBox.toggle();
				quizmaster.find('.quizMaster_question_page').hide();

		};

		/*
     * Restart quiz
		 */
		quizmaster.restartButtonInit = function() {

			quizmaster.elements.restartButton.click(function () {
					quizmaster.restartQuiz();
			});

		};

		quizmaster.restartQuiz = function () {

			quizmaster.elements.resultsBox.hide();
			quizmaster.elements.startPage.show();
			quizmaster.elements.questionList.children().hide();
			quizmaster.elements.reviewBox.hide();

			quizmaster.find('.quizMaster_questionInput, .quizMaster_cloze input').removeAttr('disabled').removeAttr('checked')
					.css('background-color', '');

			quizmaster.find('.quizMaster_questionListItem input[type="text"]').val('');

			quizmaster.find('.quizMaster_answerCorrect, .quizMaster_answerIncorrect').removeClass('quizMaster_answerCorrect quizMaster_answerIncorrect');

			quizmaster.find('.quizMaster_listItem').data('check', false);

			// quizmaster.find('.qm-check-answer-box').hide().children().hide();
			quizmaster.find('.qm-check-answer-box').hide();

			quizmaster.find('.quizMaster_sortStringItem, .quizMaster_sortable').removeAttr('style');
			quizmaster.find('.quizMaster_clozeCorrect, .quizMaster_QuestionButton, .qm-results-boxList > li').hide();

			quizmaster.find('.quizMaster_question_page, input[name="tip"]').show();
			quizmaster.find('.quizMaster_resultForm').text('').hide();

			quizmaster.elements.resultsBox.find('.qm-time-limit_expired').hide();

		};

		/*
     * Important utility functions
		 */

		quizmaster.loadQuizDataAjax = function () {

			quizmaster.ajax({
					action: 'quizmaster_admin_ajax',
					func: 'quizLoadData',
					data: {
						quizId: quizmaster.config.quizId
					}
			}, function (json) {
					quizmaster.config.globalPoints = json.globalPoints;
					quizmaster.config.catPoints = json.catPoints;
					quizmaster.config.json = json.json;
					quizmaster.find('.quizMaster_quizAnker').after(json.content);
				});
		};

		quizmaster.init = function( options ) {

			// parse options to quizmaster.config
			quizmaster.config = $.extend({

				// default settings
	      bitOptions: {
					cors: true
				},
	      options: {
					catPoints: []
				}

	    }, options );

			// convert the time limit set in seconds to ms
			quizmaster.timer.convertTimeLimitMs();

			// bind to new event
			$( quizmaster.events ).bind("questionShow", function() {

			});

			quizmaster.loadQuizDataAjax()
			quizmaster.checkButtonInit();
			quizmaster.nextButtonInit();
			quizmaster.parseBitOptions();
			quizmaster.startButtonInit();
			quizmaster.startPageShow();
			quizmaster.restartButtonInit();
			quizmaster.questionReviewButtonInit();
			quizmaster.hintInit();

    };

		/*
     * Initialize or return
		 */

 		if( !options ) {
			// return current instance
 			return quizmaster;
 		} else {
			// do init
			quizmaster.init( options );
		}


  }; // end quizmaster jQuery plugin

});
