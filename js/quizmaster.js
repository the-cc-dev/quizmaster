jQuery(document).ready(function( $ ) {

	// QuizMaster jQuery plugin
	$.fn.quizmaster = function( options = false ) {

		var quizmaster = this;

		quizmaster.config = {},
		quizmaster.status = {};

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
			skipButtonClass: '.qm-skip-button',
			singlePageLeft: 'input[name="quizMaster_pageLeft"]',
			singlePageRight: 'input[name="quizMaster_pageRight"]',
			startButton: quizmaster.find('.qm-start-button'),
			backButtonClass: '.qm-back-button',
			backButton: quizmaster.find('.qm-back-button'),
			nextButton: quizmaster.find('.qm-button-next'),
			skipButton: quizmaster.find('.qm-skip-button'),
			checkButton: quizmaster.find('.qm-button-check'),
			restartButtonClass: '.qm-restart-quiz-button',
			restartButton: quizmaster.find('.qm-restart-quiz-button'),
			questionReviewButton: quizmaster.find('.qm-question-review-button'),
			quiz: quizmaster.find('.quizMaster_quiz'),
			questionListClass: '.qm-question-list',
			questionList: quizmaster.find('.quizMaster_list'),
			resultsBox: quizmaster.find('.qm-results-box'),
			reviewBox: quizmaster.find('.qm-review-box'),
			questionCheck: quizmaster.find('.qm-check-answer-box'),
			startPage: quizmaster.find('.qm-quiz-start-box'),
			timeLimitBox: quizmaster.find('.qm-time-limit'),
			hintTrigger: quizmaster.find('.qm-hint-trigger'),
			listItems: $()
		};

		quizmaster.startPageShow = function() {

			quizmaster.elements.startPage.show();

			// hide hint button
			quizmaster.hint.buttonHide();

			// hide next button
			quizmaster.elements.nextButton.hide();

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

		/*
  		* Moves quiz to next question
		 */
		quizmaster.nextQuestion = function () {

			if( !quizmaster.data.currentQuestion.next().length ) {
				return; // no next question (end of quiz)
			}

			quizmaster.showQuestionObject( quizmaster.data.currentQuestion.next() );

			// question show event
			quizmaster.trigger({
				type: 'quizmaster.nextQuestion',
				nextQuestion: quizmaster.data.currentQuestion.next(),
				currentQuestion: quizmaster.data.currentQuestion,
			});

		};

		quizmaster.prevQuestion = function () {
			quizmaster.showQuestionObject( quizmaster.data.currentQuestion.prev() );

			quizmaster.fireChangeScreenEvent('question')
		};

		quizmaster.getCurrentQuestion = function () {
			return quizmaster.data.currentQuestion;
		}

		quizmaster.isLastQuestion = function( $questionId = 0 ) {
			if( quizmaster.questionCount() == quizmaster.data.currentQuestion.index() +1 ) {
				return true;
			}

			return false;
		}

		quizmaster.isFirstQuestion = function( $questionId ) {
			if( 0 == quizmaster.data.currentQuestion.index() ) {
				return true;
			}

			return false;
		}

		quizmaster.getCurrentQuestionId = function () {
			return quizmaster.data.currentQuestionId;
		}

		quizmaster.showQuestionObject = function ( obj ) {

			if( obj == 'current' ) {
				obj = quizmaster.data.currentQuestion;
			}

			// hide current question, show new and set storage of current question
			quizmaster.data.currentQuestion.hide();
			obj.show();
			quizmaster.setCurrentQuestion( obj );

			// change last name
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

			quizmaster.timer.question.start( quizmaster.getCurrentQuestionId() );

		};

		/*
     * Show quiz summary
		 */
		quizmaster.showQuizSummary = function() {

				quizmaster.finishQuiz();
				quizmaster.elements.reviewBox.hide();
				quizmaster.elements.quiz.hide();

		};

		quizmaster.fireChangeScreenEvent = function( $screen ) {

			// change event
			quizmaster.trigger({
				type: 'quizmaster.changeScreen',
				screen: $screen
			});

		}

		quizmaster.checkButtonInit = function() {

			quizmaster.elements.checkButton.click( function () {

				if (quizmaster.config.options.forcingQuestionSolve && !quizmaster.data.quizSolved[ quizmaster.data.currentQuestion.index() ]
					&& (quizmaster.config.options.quizSummeryHide || !quizmaster.config.options.reviewQustion)) {

					return false;
				}

				quizmaster.fireQuestionAnsweredEvent()

			});

		};

		quizmaster.userAnswerData = {

			singleMulti: function( $questionId, $questionElement ) {

				var userAnswerData = {
					answerIndexes: []
				};

				var input = $questionElement.find('.quizMaster_questionInput')

				$questionElement.find('.qm-question-list-item').each(function (i) {

					var $item = $(this);
					var index = $item.data('pos');
					var checked = input.eq(i).is(':checked');

					if( checked ) {
						userAnswerData.answerIndexes.push( index )
					}

				});

				return userAnswerData;

			},

			free: function( $questionId, $questionElement ) {

				return $questionElement.find('.quizMaster_questionInput').val();

			},

			fillBlank: function( $questionId, $questionElement ) {

				var answers = [];

				$questionElement.find('.quizMaster_cloze input').each(function (i, v) {
					answers.push( $(this).val() );
				});

				return answers;

			},

			sorting: function( $questionId, $questionElement ) {

				var answerOrder = $questionElement.find('.qm-sortable').sortable('toArray');
				return answerOrder;

			},

		};


		quizmaster.checker = function ( $questionId, $questionElement ) {

			var questionData = quizmaster.config.json[ quizmaster.getCurrentQuestionId() ];

			switch( questionData.type ) {

				case 'single':
				case 'multiple':
					var userAnswerData = quizmaster.userAnswerData.singleMulti( $questionId, $questionElement )
				break;

				case 'free_answer':
					var userAnswerData = quizmaster.userAnswerData.free( $questionId, $questionElement )
				break;

				case 'fill_blank':
					var userAnswerData = quizmaster.userAnswerData.fillBlank( $questionId, $questionElement )
				break;

				case 'sort_answer':
					var userAnswerData = quizmaster.userAnswerData.sorting( $questionId, $questionElement )
				break;

			}

			quizmaster.ajax({
					action: 'quizmaster_admin_ajax',
					func: 'checkAnswer',
					data: {

						answerType: questionData.type,
						quizId: quizmaster.config.quizId,
						userAnswerData: userAnswerData,
						questionId: $questionId,


					}
			}, function (json) {

				// organize result from checking answer
				quizmaster.data.results[ $questionId ].points = json.points;
				quizmaster.data.results[ $questionId ].correct = json.correct;
				quizmaster.data.results['comp'].points += json.points;

				if( json.correct ) {
					quizmaster.data.results['comp'].correctQuestions += 1;
				}

				quizmaster.data.catResults[ questionData.catId ] += json.points;
				quizmaster.getCurrentQuestion().data('check', true);

				// answerCheckComplete event
				quizmaster.trigger({
					type: 'quizmaster.answerCheckComplete',
					values: {
						question: quizmaster.data.currentQuestion,
						isCorrect: json.correct
					}
				});

			});

		};

		quizmaster.marker = function (e, correct) {

			if ( !quizmaster.config.options.disabledAnswerMark ) {
				if (correct) {
					e.addClass('quizMaster_answerCorrect');
				} else {
					e.addClass('quizMaster_answerIncorrect');
				}
			}

		};

		quizmaster.checkQuestion = function( list, endCheck ) {

			quizmaster.setStatus('checkQuestion')

			// stop timer
			quizmaster.timer.question.stop();

			// answer already checked
			if ( quizmaster.getCurrentQuestion().data('check') ) {
				return true;
			}

			// run checker to check answer
			quizmaster.checker( quizmaster.getCurrentQuestionId(), quizmaster.getCurrentQuestion() );

			// end check trigger
			quizmaster.trigger({
				type: 'quizmaster.questionChecked',
				values: {
					item: quizmaster.data.currentQuestion,
					index: quizmaster.data.currentQuestion.index(),
					solved: true,
					fake: true
				}
			});

		};

		quizmaster.questionSolved = function (e) {

			quizmaster.data.quizSolved[ e.values.index ] = e.values.solved;
			var data = quizmaster.config.json[ quizmaster.getCurrentQuestionId() ];

			quizmaster.data.results[data.id].solved = Number(e.values.fake ? quizmaster.data.results[data.id].solved : e.values.solved);

				// record as answered, solved/skipped
				if( e.values.fake ) {
					quizmaster.data.results.comp.answered++
					if( quizmaster.data.results[data.id].solved ) {
						quizmaster.data.results.comp.solved++
					} else {
						quizmaster.data.results.comp.skipped++
					}
				}
		};

		quizmaster.ajax = function (data, success, dataType) {
				dataType = dataType || 'json';

				if (quizmaster.config.options.cors) {
						jQuery.support.cors = true;
				}

				$.post(QuizMasterGlobal.ajaxurl, data, success, dataType);

				if (quizmaster.config.options.cors) {
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

				if ( quizmaster.config.options.forcingQuestionSolve && !quizmaster.data.quizSolved[ quizmaster.getCurrentQuestion().index() ]
					&& ( quizmaster.config.options.quizSummeryHide || !quizmaster.config.options.reviewQustion )) {
					return false;
				}

				// question answered event
				// don't fire for mode 1 (check/continue) because it's fired from check button click
				if( quizmaster.config.mode != 1 ) {
					quizmaster.fireQuestionAnsweredEvent()
				}

				quizmaster.nextQuestion();

			});

		};

		quizmaster.fireQuestionAnsweredEvent = function() {

			// question answered event
			quizmaster.trigger({
				type: 'quizmaster.questionAnswered',
				question: quizmaster.data.currentQuestion,
			});

		};

		quizmaster.backButtonInit = function() {

			quizmaster.elements.backButton.click( function () {
				quizmaster.prevQuestion();
			});

		}

		quizmaster.startQuiz = function() {

			quizmaster.startPageHide();
			quizmaster.loadQuizData();

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

			// maybe show reviewBox
			if( quizmaster.config.options.isShowReviewQuestion ) {
				quizmaster.elements.reviewBox.show();
			}

			// maybe show skip button
			if ( quizmaster.config.options.isShowSkipButton || quizmaster.config.options.isShowReviewQuestion ) {
				quizmaster.elements.skipButton.show();
			}

			// maybe show back button
			if ( quizmaster.config.options.isShowBackButton ) {
				quizmaster.elements.backButton.show();
			}

			// start timer
			quizmaster.timer.quiz.start();

			// quiz start event
			quizmaster.trigger({
				type: 'quizmaster.startQuiz',
				mode: quizmaster.config.mode,
			});

		};

		quizmaster.showSinglePage = function (page) {
				$listItem = quizmaster.elements.questionList.children().hide();

				if (!quizmaster.config.qpp) {
						$listItem.show();

						return;
				}

				page = page ? +page : 1;
				var maxPage = Math.ceil(quizmaster.find('.quizMaster_list > li').length / quizmaster.config.qpp);

				if (page > maxPage)
						return;

				var pl = quizmaster.find(quizmaster.elements.singlePageLeft).hide();
				var pr = quizmaster.find(quizmaster.elements.singlePageRight).hide();
				var cs = quizmaster.find('input[name="checkSingle"]').hide();

				if (page > 1) {
						pl.val(pl.data('text').replace(/%d/, page - 1)).show();
				}

				if (page == maxPage) {
					cs.show();
				} else {
					pr.val(pr.data('text').replace(/%d/, page + 1)).show();
				}

				currentPage = page;
				var start = config.qpp * (page - 1);

				$listItem.slice(start, start + config.qpp).show();
				quizmaster.scrollTo( quizmaster.elements.quiz );
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

			// check all answers if mode is single page
			if( quizmaster.config.mode == 2 ) {

				console.log( 'checking all stacked mode')

				quizmaster.checkQuestion(quizmaster.elements.questionList.children(), true);
			}

			/*
       * Show the quiz summary
       * Needs to fire after question check for last question is completed
			 */

			// show the correct answer count
			var correctAnswerEl = quizmaster.find('.quizMaster_correct_answer');
			correctAnswerEl.text( quizmaster.data.results.comp.correctQuestions )

			quizmaster.data.results.comp.result = Math.round(quizmaster.data.results.comp.points / quizmaster.config.globalPoints * 100 * 100) / 100;
			var $pointFields = quizmaster.find('.quizMaster_points span');

			$pointFields.eq(0).text(quizmaster.data.results.comp.points);
			$pointFields.eq(1).text(quizmaster.config.globalPoints);
			$pointFields.eq(2).text(quizmaster.data.results.comp.result + '%');


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
			quizmaster.trigger({
				type: 'quizmaster.quizCompleted',
				questionCount: quizmaster.questionCount(),
				results: quizmaster.data.results,
			});

			// reset result comp
			quizmaster.data.results.comp.solved 	= 0;
			quizmaster.data.results.comp.answered = 0;
			quizmaster.data.results.comp.skipped 	= 0;

		};

		/*
     * Hint Handler Functions
		 */

		quizmaster.hint = {

			buttonHide: function() {
				quizmaster.elements.hintTrigger.hide();
			},

			buttonShow: function() {
				quizmaster.elements.hintTrigger.show();
			},

		};

		 quizmaster.hintInit = function() {

 			quizmaster.on('quizmaster.questionShow', function() {

				var $hint = quizmaster.getCurrentQuestion().find('.quizMaster_tipp')
				if( ! $hint.length ) {
					quizmaster.hintDisable();
				} else {
					quizmaster.hint.buttonShow();
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

				},

				stop: function () {

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

				var seconds = parseInt(ms / 1000);
				var minutes = parseInt((seconds / 60) % 60);
				var hours = parseInt((seconds / 3600) % 24);

				seconds = (seconds > 9 ? '' : '0') + seconds;
				minutes = (minutes > 9 ? '' : '0') + minutes;
				hours = (hours > 9 ? '' : '0') + hours;

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

				quizmaster.find('.quizMaster_question-list').each(function () {
						var $this = $(this);
						var questionId = $this.data('question_id');
						var type = $this.data('type');
						var data = {};

						if (type == 'single' || type == 'multiple') {
								$this.find('.qm-question-list-item').each(function () {
									data[$(this).data('pos')] = +$(this).find('.quizMaster_questionInput').is(':checked');
								});
						} else if (type == 'free_answer') {
								data[0] = $this.find('.quizMaster_questionInput').val();
						} else if (type == 'sort_answer') {
								return true;
						} else if (type == 'matrix_sort_answer') {
								return true;
						} else if (type == 'fill_blank') {
								var i = 0;
								$this.find('.quizMaster_cloze input').each(function () {
										data[i++] = $(this).val();
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

				if( quizmaster.config.showReviewBox ) {
					quizmaster.elements.reviewBox.toggle();
				}

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

		quizmaster.modeHandler = function() {

			// mode handling
			switch (quizmaster.config.mode) {

				// single page mode
				case 2:

					quizmaster.elements.nextButton.show();
					quizmaster.find('.quizMaster_question_page').hide();
					var $questionList = quizmaster.elements.questionList.children();
					quizmaster.setCurrentQuestion( $questionList.last() );
					quizmaster.showSinglePage(0);
					break;

				// check/continue mode
				case 1:

					// show check button at start
					quizmaster.elements.checkButton.show();

					// handle buttons on questionCheck
					quizmaster.on( 'quizmaster.questionChecked', function() {
						quizmaster.elements.nextButton.show()
						quizmaster.elements.checkButton.hide()
					});

					// handle buttons on nextQuestion
					quizmaster.on( 'quizmaster.nextQuestion', function() {
						quizmaster.elements.nextButton.hide()
						quizmaster.elements.checkButton.show()
					});

					break;

				// default normal mode
				case 0:
					quizmaster.elements.nextButton.show();
					break;
			}

			// maybe hide question position overview
			if ( quizmaster.config.options.hideQuestionPositionOverview ) {
				quizmaster.find('.quizMaster_question_page').hide();
			}

			// start timer
			quizmaster.timer.question.start( quizmaster.getCurrentQuestionId() )

		};

		quizmaster.startQuizShowQuestion = function() {

			if( quizmaster.config.mode != 2 ) {

				// get first question object and show
				var $questionList = quizmaster.elements.questionList.children();
				quizmaster.setCurrentQuestion( $questionList.eq(0) );
				quizmaster.showQuestionObject( 'current' );

			}

		};

		quizmaster.sortableInit = function () {

			quizmaster.find('.qm-sortable').sortable({
				update: function (event, ui) {
					var $p = $(this).parents('.quizMaster_listItem');

					quizmaster.trigger({
							type: 'quizmaster.questionSolved',
							values: {
								item: $p,
								index: $p.index(),
								solved: true
							}
					});
				}
			}).disableSelection();

		}

		quizmaster.setStatus = function ( statusCode ) {
			quizmaster.status = statusCode;
		}

		quizmaster.getStatus = function () {
			return quizmaster.status;
		}

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

			quizmaster.loadQuizDataAjax()
			quizmaster.checkButtonInit();
			quizmaster.backButtonInit();
			quizmaster.nextButtonInit();
			quizmaster.startButtonInit();
			quizmaster.startPageShow();
			quizmaster.restartButtonInit();
			quizmaster.questionReviewButtonInit();
			quizmaster.hintInit();
			quizmaster.sortableInit();

			quizmaster.on( 'quizmaster.startQuiz', quizmaster.modeHandler );
			quizmaster.on( 'quizmaster.startQuiz', quizmaster.startQuizShowQuestion );

			// bind questionSolved to questionCheck
			quizmaster.on( 'quizmaster.questionChecked', quizmaster.questionSolved );

			// bind to quizCompleted event
			quizmaster.on( 'quizmaster.quizCompleted', function() {
				quizmaster.elements.nextButton.hide()
				quizmaster.elements.checkButton.hide()
				quizmaster.elements.hintTrigger.hide()
			});

			// bind to questionAnswered event
			quizmaster.on( 'quizmaster.questionAnswered', function() {
				quizmaster.checkQuestion()
			});

			// show quiz summary and finish quiz if no more questions left in quiz
			quizmaster.on( 'quizmaster.answerCheckComplete', function() {

				if( !quizmaster.data.currentQuestion.next().length ) {
					quizmaster.showQuizSummary();
				}

			});

			/*
 			 * Set initial status
			 */
			quizmaster.setStatus('initialized')

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
