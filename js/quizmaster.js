jQuery(document).ready(function( $ ) {

	// QuizMaster jQuery plugin
	$.quizmaster = function( element, options ) {

		var plugin = this;

		plugin.element = $( element ),
		plugin.config = {},
		plugin.status = {};
		plugin.restarted = false,
		plugin.finish = false;

		plugin.data = {
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

		plugin.elements = {
			checkButtonClass: '.qm-button-check',
			nextButtonClass: '.qm-button-next',
			finishButtonClass: '.qm-button-finish',
			skipButtonClass: '.qm-skip-button',
			singlePageLeft: 'input[name="quizMaster_pageLeft"]',
			singlePageRight: 'input[name="quizMaster_pageRight"]',
			startButton: plugin.element.find('.qm-start-button'),
			backButtonClass: '.qm-back-button',
			backButton: plugin.element.find('.qm-back-button'),
			nextButton: plugin.element.find('.qm-button-next'),
			finishButton: plugin.element.find('.qm-button-finish'),
			skipButton: plugin.element.find('.qm-skip-button'),
			checkButton: plugin.element.find('.qm-button-check'),
			restartButtonClass: '.qm-restart-quiz-button',
			restartButton: plugin.element.find('.qm-restart-quiz-button'),
			questionReviewButton: plugin.element.find('.qm-question-review-button'),
			quiz: plugin.element.find('.quizMaster_quiz'),
			questionListClass: '.qm-question-list',
			questionList: plugin.element.find('.quizMaster_list'),
			resultsBox: plugin.element.find('.qm-results-box'),
			reviewBox: plugin.element.find('.qm-review-box'),
			questionCheck: plugin.element.find('.qm-check-answer-box'),
			startPage: plugin.element.find('.qm-quiz-start-box'),
			timeLimitBox: plugin.element.find('.qm-time-limit'),
			hintTrigger: plugin.element.find('.qm-hint-trigger'),
			listItems: $()
		};

		plugin.startPageShow = function() {

			console.log( plugin.config )
			console.log(JSON.stringify( plugin.config.lock ));

			if( plugin.config.lock.locked == false ) {
				plugin.elements.startPage.show();
			}

			// hide hint button
			plugin.hint.buttonHide();

			// hide next button
			plugin.elements.nextButton.hide();

			// hide next button
			plugin.elements.finishButton.hide();

			plugin.element.trigger({
				type: 'quizmaster.startPageShow',
				plugin: plugin,
			});

		};

		plugin.startPageHide = function() {
			plugin.elements.startPage.hide();
		};

		/*
  		* Moves quiz to next question
		 */
		plugin.nextQuestion = function () {

			if( !plugin.data.currentQuestion.next().length ) {
				return; // no next question (end of quiz)
			}

			plugin.showQuestionObject( plugin.data.currentQuestion.next() );

			// question show event
			plugin.element.trigger({
				type: 'quizmaster.nextQuestion',
				nextQuestion: plugin.data.currentQuestion.next(),
				currentQuestion: plugin.data.currentQuestion,
			});

		};

		plugin.prevQuestion = function () {
			plugin.showQuestionObject( plugin.data.currentQuestion.prev() );

			plugin.fireChangeScreenEvent('question')
		};

		plugin.getCurrentQuestion = function () {
			return plugin.data.currentQuestion;
		}

		plugin.isLastQuestion = function( $question ) {

			if( $question == undefined ) {
				$question = plugin.getCurrentQuestion()
			}

			if( plugin.questionCount() == $question.index() +1 ) {
				return true;
			}

			return false;

		}

		plugin.isFirstQuestion = function( $questionId ) {
			if( 0 == plugin.data.currentQuestion.index() ) {
				return true;
			}

			return false;
		}

		plugin.getCurrentQuestionId = function () {
			return plugin.data.currentQuestionId;
		}

		plugin.showQuestionObject = function ( obj ) {

			if( obj == 'current' ) {
				obj = plugin.data.currentQuestion;
			}

			// hide current question, show new and set storage of current question
			plugin.data.currentQuestion.hide();
			obj.show();
			plugin.setCurrentQuestion( obj );

			// scroll to quiz area
			plugin.scrollTo( plugin.elements.quiz );

			// question show event
			plugin.element.trigger({
				type: 'quizmaster.questionShow',
				question: plugin.data.currentQuestion,
				questionIndex: plugin.data.currentQuestion.index()
			});

			// last question load event
			if( plugin.questionCount() == plugin.data.currentQuestion.index() +1 ) {

				plugin.element.trigger({
					type: 'quizmaster.lastQuestionLoaded',
					question: plugin.data.currentQuestion,
					questionIndex: plugin.data.currentQuestion.index()
				});

			}

			plugin.timer.question.start( plugin.getCurrentQuestionId() );

		};

		plugin.fireChangeScreenEvent = function( $screen ) {

			// change event
			plugin.element.trigger({
				type: 'quizmaster.changeScreen',
				screen: $screen
			});

		}

		plugin.checkButtonInit = function() {

			plugin.elements.checkButton.click( function () {

				if (plugin.config.options.forcingQuestionSolve && !plugin.data.quizSolved[ plugin.data.currentQuestion.index() ]
					&& (plugin.config.options.quizSummeryHide || !plugin.config.options.reviewQustion)) {

					return false;
				}

				plugin.fireQuestionAnsweredEvent()

			});

		};

		plugin.userAnswerData = {

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

		/*
 		 * Get Question Input
 		 * Usually checkbox, radio button element
 		 * Defaults to input from current question
		 */
		plugin.getQuestionInput = function( $question ) {

			if( $question == undefined ) {
				$question = plugin.getCurrentQuestion();
			}

			 return $question.find('.quizMaster_questionInput');

		}

		/*
 		 * Get Question Data
 		 * Question data stored in json array
 		 * Key is the question id, pass question id to load specific question data
 		 * Default return is current question loaded into quiz
		 */
		plugin.getQuestionData = function( $questionId ) {

			if( $questionId == undefined ) {
				$questionId = plugin.getCurrentQuestionId();
			}

			return plugin.config.json[ $questionId ];

		}

		plugin.checker = function ( $questionId, $questionElement ) {

			var questionData = plugin.config.json[ plugin.getCurrentQuestionId() ];

			switch( questionData.type ) {

				case 'single':
				case 'multiple':
					var userAnswerData = plugin.userAnswerData.singleMulti( $questionId, $questionElement )
				break;

				case 'free_answer':
					var userAnswerData = plugin.userAnswerData.free( $questionId, $questionElement )
				break;

				case 'fill_blank':
					var userAnswerData = plugin.userAnswerData.fillBlank( $questionId, $questionElement )
				break;

				case 'sort_answer':
					var userAnswerData = plugin.userAnswerData.sorting( $questionId, $questionElement )
				break;

			}

			plugin.ajax({
					action: 'quizmaster_admin_ajax',
					func: 'checkAnswer',
					data: {

						answerType: questionData.type,
						quizId: plugin.config.quizId,
						userAnswerData: userAnswerData,
						questionId: $questionId,


					}
			}, function (json) {

				// organize result from checking answer
				plugin.data.results[ $questionId ].points = json.points;
				plugin.data.results[ $questionId ].correct = json.correct;
				plugin.data.results['comp'].points += json.points;

				if( json.correct ) {
					plugin.data.results['comp'].correctQuestions += 1;
				}

				plugin.data.catResults[ questionData.catId ] += json.points;
				plugin.getCurrentQuestion().data('check', true);

				// answerCheckComplete event
				plugin.element.trigger({
					type: 'quizmaster.answerCheckComplete',
					question: plugin.getCurrentQuestion(),
					isCorrect: json.correct,
					pointsEarned: json.points,
				});

			});

		};

		plugin.setCheckMessagePoints = function( $pointsEarned ) {
			$('.qm-check-question-points span').text( $pointsEarned );
		}

		plugin.setCheckMessage = function ( $isCorrect, $pointsEarned ) {

			$questionData = plugin.getQuestionData();

			// points
			plugin.setCheckMessagePoints( $pointsEarned )

			// messages
			if ( $isCorrect ) {
				// correct answer

				$('.qm-check-message').html( $questionData.correctMessage )
				$('.qm-check-message').removeClass('qm-check-answer-incorrect')
				$('.qm-check-message').addClass('qm-check-answer-correct')

	    } else {
				$('.qm-check-message').html( $questionData.incorrectMessage )
				$('.qm-check-message').removeClass('qm-check-answer-correct')
				$('.qm-check-message').addClass('qm-check-answer-incorrect')
			}

			plugin.checkMessageBoxShow()

		};

		plugin.checkMessageBoxShow = function() {
			// show check message
			$('.qm-check-answer-box').show()
			$('.qm-check-message').show()
			$('.qm-check-result').show()
		}

		plugin.checkMessageBoxHide = function() {
			$('.qm-check-answer-box').hide()
			$('.qm-check-message').hide()
			$('.qm-check-result').hide()
		}

		plugin.getQuestions = function() {
			return plugin.elements.questionList.children();
		}

		/*
     * Checks multiple questions
     * Used for single page (stacked mode) quizzes where all answers submitted at once
		 */
		plugin.checkQuestionMultiple = function() {

			plugin.setStatus('check_question_multiple')

			// get all questions
			var $questionList = plugin.getQuestions();

			$questionList.each( function( index, element ){

				$question = $(this);
				plugin.setCurrentQuestion( $question );
				plugin.checker( plugin.getCurrentQuestionId(), plugin.getCurrentQuestion() );

				// after last question checked do finishQuiz()
				if( plugin.isLastQuestion( $question ) ) {

					plugin.finish = true;
					plugin.element.on( 'quizmaster.answerCheckComplete', function( e ) {

						$question = e.question;
						if( plugin.isLastQuestion( $question ) ) {
							plugin.finishQuiz();
						}

					});
				}

			});

			// questions.each plugin.checkQuestion()

		}

		/*
     * Checks a single question
		 */
		plugin.checkQuestion = function() {

			// move this so the function can be used by multiple check
			plugin.setStatus('check_question')

			// answer already checked
			if ( plugin.getCurrentQuestion().data('check') ) {
				return true;
			}

			// run checker to check answer
			plugin.checker( plugin.getCurrentQuestionId(), plugin.getCurrentQuestion() );

			// end check trigger
			plugin.element.trigger({
				type: 'quizmaster.questionChecked',
				values: {
					item: plugin.data.currentQuestion,
					index: plugin.data.currentQuestion.index(),
					solved: true,
					fake: true
				}
			});

		};

		plugin.questionSolved = function (e) {

			plugin.data.quizSolved[ e.values.index ] = e.values.solved;
			var data = plugin.config.json[ plugin.getCurrentQuestionId() ];

			plugin.data.results[data.id].solved = Number(e.values.fake ? plugin.data.results[data.id].solved : e.values.solved);

				// record as answered, solved/skipped
				if( e.values.fake ) {
					plugin.data.results.comp.answered++
					if( plugin.data.results[data.id].solved ) {
						plugin.data.results.comp.solved++
					} else {
						plugin.data.results.comp.skipped++
					}
				}
		};

		plugin.ajax = function (data, success, dataType) {
				dataType = dataType || 'json';

				if (plugin.config.options.cors) {
						jQuery.support.cors = true;
				}

				$.post(QuizMasterGlobal.ajaxurl, data, success, dataType);

				if (plugin.config.options.cors) {
						jQuery.support.cors = false;
				}
		};

		plugin.startButtonInit = function() {

			plugin.elements.startButton.click( function () {
				plugin.startQuiz();
			});

		};

		/*
     * Initialize Next Button
		 */
		plugin.nextButtonInit = function() {

			plugin.elements.nextButton.click(function () {

				if ( plugin.config.options.forcingQuestionSolve && !plugin.data.quizSolved[ plugin.getCurrentQuestion().index() ]
					&& ( plugin.config.options.quizSummeryHide || !plugin.config.options.reviewQustion )) {
					return false;
				}

				// question answered event
				plugin.fireQuestionAnsweredEvent()

			});

		};

		plugin.nextButtonInitCheckContinueMode = function() {

			plugin.elements.nextButton.click(function () {

				if ( plugin.config.options.forcingQuestionSolve && !plugin.data.quizSolved[ plugin.getCurrentQuestion().index() ]
					&& ( plugin.config.options.quizSummeryHide || !plugin.config.options.reviewQustion )) {
					return false;
				}

				// question answered event
				plugin.nextQuestion()

			});

		};

		/*
     * Initialize Finish Button
		 */
		plugin.finishButtonInit = function() {

			plugin.elements.finishButton.click(function () {

				plugin.finish = true;
				plugin.fireQuestionAnsweredEvent()

			});

		};

		plugin.finishButtonInitFinishQuiz = function() {

			plugin.elements.finishButton.click(function () {

				plugin.finishQuiz()

			});

		};

		plugin.fireQuestionAnsweredEvent = function() {

			// question answered event
			plugin.element.trigger({
				type: 'quizmaster.questionAnswered',
				question: plugin.getCurrentQuestion(),
			});

		};

		plugin.backButtonInit = function() {

			plugin.elements.backButton.click( function () {
				plugin.prevQuestion();
			});

		}

		plugin.startQuiz = function() {

			plugin.startPageHide();

			var $listItem = plugin.elements.questionList.children();
			plugin.elements.listItems = $('.quizMaster_list > li');

			// start time limit
			plugin.timer.limit.start();

			plugin.data.quizSolved = [];
			plugin.data.results = {
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

					plugin.data.results[ questionId ] = {
						time: 0,
						solved: 0
					};

			});

			plugin.data.catResults = {};

			$.each( plugin.config.options.catPoints, function (i, v) {
				plugin.data.catResults[i] = 0;
			});

			plugin.elements.quiz.show();

			// maybe show reviewBox
			if( plugin.config.options.isShowReviewQuestion ) {
				plugin.elements.reviewBox.show();
			}

			// maybe show skip button
			if ( plugin.config.options.isShowSkipButton || plugin.config.options.isShowReviewQuestion ) {
				plugin.elements.skipButton.show();
			}

			// maybe show back button
			if ( plugin.config.options.isShowBackButton ) {
				plugin.elements.backButton.show();
			}

			// start timer
			plugin.timer.quiz.start();

			// determine if this is a restart
			var restart = false;
			if( plugin.getStatus() == 'restart' ) {
				restart = true;
			}

			// quiz start event
			plugin.element.trigger({
				type: 'quizmaster.startQuiz',
				mode: plugin.config.mode,
				restart: restart,
			});

			// change status
			plugin.setStatus('started');

		};

		plugin.showSinglePage = function (page) {
				$listItem = plugin.elements.questionList.children().hide();

				if (!plugin.config.qpp) {
						$listItem.show();

						return;
				}

				page = page ? +page : 1;
				var maxPage = Math.ceil(plugin.element.find('.quizMaster_list > li').length / plugin.config.qpp);

				if (page > maxPage)
						return;

				var pl = plugin.element.find(plugin.elements.singlePageLeft).hide();
				var pr = plugin.element.find(plugin.elements.singlePageRight).hide();
				var cs = plugin.element.find('input[name="checkSingle"]').hide();

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
				plugin.scrollTo( plugin.elements.quiz );
		};

		plugin.setCurrentQuestion = function( $question ) {

			plugin.data.currentQuestion = $question;
			plugin.data.currentQuestionId = $question.find(plugin.elements.questionListClass).data('question_id');

		};

		plugin.questionCount = function () {
			return plugin.element.find('.quizMaster_listItem').length;
		};

		plugin.finishQuiz = function (timeover) {

			// when quiz mode is single page and not set to finish ready state, do check question multiple
			// after checkQuestionMultiple() checks all the questions plugin.finish is set to true
			if( plugin.config.mode == 2 && plugin.finish == false ) {
				plugin.checkQuestionMultiple()
				return;
			}

			// hide finish button
			plugin.elements.finishButton.hide();

			// deactivate hint trigger
			plugin.hintDisable();

			plugin.timer.question.stop();
			plugin.timer.quiz.stop();
			plugin.timer.limit.stop();

			var time = (+new Date() - plugin.timer.quizStartTime);
			time = (plugin.config.timeLimit && time > plugin.config.timeLimit) ? plugin.config.timeLimit : time;

			plugin.element.find('.quizMaster_quiz_time span').text( plugin.timer.parseTime(time) );

			if (timeover) {
				plugin.elements.resultsBox.find('.qm-time-limit_expired').show();
			}

			// average result
			plugin.data.results.comp.result = Math.round(plugin.data.results.comp.points / plugin.config.globalPoints * 100 * 100) / 100;

			plugin.setAverageResult(plugin.data.results.comp.result, false);
			plugin.setCategoryOverview();
			plugin.sendCompletedQuiz();

			/* global trigger */
			plugin.element.trigger({
				type: 'quizmaster.quizCompleted',
				questionCount: plugin.questionCount(),
				results: plugin.data.results,
			});

		};

		plugin.afterQuizFinish = function() {

			plugin.elements.reviewBox.hide();
			plugin.elements.quiz.hide();

			// show the correct answer count
			var correctAnswerEl = plugin.element.find('.quizMaster_correct_answer');
			correctAnswerEl.text( plugin.data.results.comp.correctQuestions )

			var $pointFields = plugin.element.find('.quizMaster_points span');

			$pointFields.eq(0).text(plugin.data.results.comp.points);
			$pointFields.eq(1).text(plugin.config.globalPoints);
			$pointFields.eq(2).text(plugin.data.results.comp.result + '%');

			// hide buttons and elements
			plugin.elements.nextButton.hide()
			plugin.elements.hintTrigger.hide()
			plugin.elements.checkButton.hide();
			plugin.elements.skipButton.hide();
			plugin.elements.finishButton.hide();
			plugin.elements.reviewBox.hide();
			plugin.element.find('.qm-check-page, .qm-info-page').hide();
			plugin.elements.quiz.hide();
			plugin.elements.resultsBox.show();
			plugin.scrollTo(plugin.elements.resultsBox);

		}

		/*
     * ScrollTo
		 */
		 plugin.scrollTo = function (e, h) {
       var x = e.offset().top - 100;

       if (h || (window.pageYOffset || document.body.scrollTop) > x) {
         $('html,body').animate({scrollTop: x}, 300);
       }
     }

		/*
     * Hint Handler Functions
		 */

		plugin.hint = {

			buttonHide: function() {
				plugin.elements.hintTrigger.hide();
			},

			buttonShow: function() {
				plugin.elements.hintTrigger.show();
			},

		};

		 plugin.hintInit = function() {

 			plugin.element.on('plugin.questionShow', function() {

				var $hint = plugin.getCurrentQuestion().find('.quizMaster_tipp')
				if( ! $hint.length ) {
					plugin.hintDisable();
				} else {
					plugin.hint.buttonShow();
					plugin.hintEnable();
				}

 			});
 		};

		 plugin.hintDisable = function () {

 			$tipModal = $('.qm-hint-modal');
 			$tipModal.hide();
			plugin.elements.hintTrigger.hide()
 			plugin.elements.hintTrigger.removeClass('qm-hint-enabled')
 			plugin.elements.hintTrigger.addClass('qm-hint-disabled')
 			plugin.elements.hintTrigger.off( 'click', plugin.hintHide )
 			plugin.elements.hintTrigger.off( 'click', plugin.hintShow )

 		};

 		plugin.hintEnable = function () {

 			plugin.elements.hintTrigger.removeClass('qm-hint-disabled')
 			plugin.elements.hintTrigger.addClass('qm-hint-enabled')
 			plugin.elements.hintTrigger.off( 'click', plugin.hintHide )
 			plugin.elements.hintTrigger.on( 'click', plugin.hintShow )

 		};

 		plugin.hintHide = function ( event ) {

 			$tipModal = $('.qm-hint-modal');
 			$tipModal.hide();
 			plugin.elements.hintTrigger.off( 'click', plugin.hintHide )
 			plugin.elements.hintTrigger.on( 'click', plugin.hintShow )

 		};

 		plugin.hintShow = function ( event ) {

 			var $this = $(this);
 			var id = plugin.getCurrentQuestionId();

 			// get tip div
 			var $hint = plugin.data.currentQuestion.find('.quizMaster_tipp')
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

 			plugin.elements.hintTrigger.on( 'click', plugin.hintHide )
 			plugin.elements.hintTrigger.off( 'click', plugin.hintShow )

 			// record use of tip
 			plugin.data.results[id].tip = 1;

 		};

		/*
     * Timer Class
		 */
		plugin.timer = {

			questionStartTime: 0,
			quizStartTime: 0,

			limit: {

				intervalId: 0,

				stop: function () {
					if ( plugin.config.timeLimit ) {
						window.clearInterval( plugin.timer.limit.intervalId );
						plugin.elements.timeLimitBox.hide();
					}
				},

				start: function () {

					if (! plugin.config.timeLimit )
						return;

					var $timeText = plugin.elements.timeLimitBox.find('span').text( plugin.timer.parseTime( plugin.config.timeLimit ) );
					var $timeDiv = plugin.elements.timeLimitBox.find('.qm-progress-box');

					plugin.elements.timeLimitBox.show();

					var beforeTime = +new Date();

					plugin.timer.limit.intervalId = window.setInterval(function () {

						var diff = (+new Date() - beforeTime);
						var elapsedTime = (plugin.config.timeLimit) - diff;

						if (diff >= 500) {
							$timeText.text( plugin.timer.parseTime(Math.ceil(elapsedTime)) );
						}

						$timeDiv.css('width', (elapsedTime / plugin.config.timeLimit * 100) + '%');

						if (elapsedTime <= 0) {
							plugin.timer.limit.stop();
							plugin.finishQuiz( true );
						}

					});
				},

			},

			question: {

				start: function ( questionId ) {
					if ( plugin.data.currentQuestionId != 0 )
						plugin.timer.question.stop();

					plugin.data.currentQuestionId = questionId;
					plugin.timer.questionStartTime = +new Date();

				},

				stop: function () {

					if ( plugin.getCurrentQuestionId() == 0 )
							return;

					plugin.data.results[ plugin.getCurrentQuestionId() ].time += Math.round((new Date() - plugin.timer.questionStartTime));

				},

			},

			quiz: {

				start: function () {

					plugin.timer.quizStartTime = +new Date();
					plugin.data.isQuizStarted = true;

				},

				stop: function () {

					if ( !plugin.data.isQuizStarted ) {
						return;
					}

					plugin.data.results['comp'].quizTime += new Date() - plugin.timer.quizStartTime;
					plugin.data.isQuizStarted = false;

				},

			},

			convertTimeLimitMs: function() {
				if( plugin.config.timeLimit ) {
					plugin.config.timeLimit = plugin.config.timeLimit * 1000;
				}
			},

			parseTime: function (ms) {

				x = ms / 1000
				seconds = parseInt( x % 60 )
				x /= 60
				minutes = parseInt( x % 60 )
				x /= 60
				hours = parseInt( x % 24 )



				seconds = (seconds > 9 ? '' : '0') + seconds;
				minutes = (minutes > 9 ? '' : '0') + minutes;
				hours = (hours > 9 ? '' : '0') + hours;

				return hours + ':' + minutes + ':' + seconds;
			},

		};

		plugin.setAverageResult = function (p, g) {
			var v = plugin.element.find('.quizMaster_resultValue:eq(' + (g ? 0 : 1) + ') > * ');
			v.eq(1).text(p + '%');
			v.eq(0).css('width', (240 * p / 100) + 'px');
		};

		plugin.setCategoryOverview = function () {

				plugin.data.results.comp.cats = {};

				plugin.element.find('.quizMaster_catOverview li').each(function () {

					var $this = $(this);
					var catId = $this.data('category_id');

					if (plugin.config.catPoints[catId] === undefined) {
							$this.hide();
							return true;
					}

					var r = Math.round(plugin.data.catResults[catId] / plugin.config.catPoints[catId] * 100 * 100) / 100;

					plugin.data.results.comp.cats[catId] = r;

					$this.find('.quizMaster_catPercent').text(r + '%');

					$this.show();
				});

		};

		plugin.sendCompletedQuiz = function () {

			plugin.fetchAllAnswerData( plugin.data.results );

			plugin.ajax({
				action: 'quizmaster_admin_ajax',
				func: 'completedQuiz',
				data: {
					quizId: plugin.config.quizId,
					results: plugin.data.results,
				}
			});

		};

		plugin.fetchAllAnswerData = function (resultData) {

				plugin.element.find('.quizMaster_question-list').each(function () {
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

						plugin.data.resultData[questionId]['data'] = data;

				});
		};

		/*
     * Question Review
		 */
		plugin.questionReviewButtonInit = function() {

			plugin.elements.questionReviewButton.on( 'click', function () {
				plugin.showQuestionList();
			});

		};

		plugin.showQuestionList = function () {

				plugin.elements.quiz.toggle();
				plugin.element.find('.quizMaster_QuestionButton').hide();
				plugin.elements.questionList.children().show();

				if( plugin.config.showReviewBox ) {
					plugin.elements.reviewBox.toggle();
				}

				plugin.element.find('.quizMaster_question_page').hide();

		};

		/*
     * Restart quiz
		 */
		plugin.restartButtonInit = function() {

			plugin.elements.restartButton.click(function () {
					plugin.restartQuiz();
			});

		};

		plugin.restartQuiz = function () {

			// flag that the quiz has been restarted
			plugin.restarted = true;

			// reset current question
			var $questionList = plugin.elements.questionList.children();
			plugin.setCurrentQuestion( $questionList.eq(0) );

			plugin.elements.resultsBox.hide();
			plugin.elements.startPage.show();
			plugin.elements.questionList.children().hide();
			plugin.elements.reviewBox.hide();

			plugin.element.find('.quizMaster_questionInput, .quizMaster_cloze input').removeAttr('disabled').removeAttr('checked')
					.css('background-color', '');

			plugin.element.find('.quizMaster_questionListItem input[type="text"]').val('');

			plugin.element.find('.quizMaster_answerCorrect, .quizMaster_answerIncorrect').removeClass('quizMaster_answerCorrect quizMaster_answerIncorrect');

			plugin.element.find('.quizMaster_listItem').data('check', false);

			// plugin.element.find('.qm-check-answer-box').hide().children().hide();
			plugin.element.find('.qm-check-answer-box').hide();
			plugin.element.find('.quizMaster_clozeCorrect, .quizMaster_QuestionButton, .qm-results-boxList > li').hide();

			plugin.element.find('.quizMaster_question_page, input[name="tip"]').show();
			plugin.element.find('.quizMaster_resultForm').text('').hide();

			plugin.elements.resultsBox.find('.qm-time-limit_expired').hide();

			// reset finish tracker
			plugin.finish = false;

			// set status
			plugin.setStatus('restart')

		};

		/*
     * Important utility functions
		 */

		plugin.loadQuizData = function () {

			plugin.ajax({
					action: 'quizmaster_admin_ajax',
					func: 'quizLoadData',
					data: {
						quizId: plugin.config.quizId
					}
			}, function (json) {

				plugin.config.globalPoints = json.globalPoints;
				plugin.config.catPoints = json.catPoints;
				plugin.config.json = json.json;
				plugin.element.find('.quizMaster_quizAnker').after(json.content);

				// quiz data loaded event
				$( document ).trigger({
					type: 'quizmaster.quizDataLoaded',
					quizmaster: plugin
				});

			});
		};

		plugin.modeHandler = function( e ) {

			var restart = e.restart;

			// mode handling
			switch (plugin.config.mode) {

				// single page mode
				case 2:

					plugin.elements.finishButton.show();
					plugin.element.find('.quizMaster_question_page').hide();
					var $questionList = plugin.elements.questionList.children();
					plugin.setCurrentQuestion( $questionList.last() );
					plugin.showSinglePage(0);
					plugin.finishButtonInitFinishQuiz();
					plugin.nextButtonInit();

					break;

				// check/continue mode
				case 1:

					// show check button at start
					plugin.elements.checkButton.show();
					plugin.elements.finishButton.hide();
					plugin.elements.nextButton.hide();

					// handle buttons on questionCheck
					if( !restart ) {

						plugin.element.on( 'quizmaster.questionChecked', function() {

							if( plugin.isLastQuestion() ) {
								plugin.elements.finishButton.show()
								plugin.elements.checkButton.hide()
							} else {
								plugin.elements.nextButton.show()
								plugin.elements.checkButton.hide()
							}

						});

						// handle buttons on nextQuestion
						plugin.element.on( 'quizmaster.nextQuestion', function() {

							plugin.elements.checkButton.show()
							plugin.elements.nextButton.hide()

							plugin.checkMessageBoxHide()

						});

						plugin.finishButtonInitFinishQuiz();
						plugin.nextButtonInitCheckContinueMode();

					}

					// answer check completed
					plugin.element.on( 'quizmaster.answerCheckComplete', function( e ) {

						// get check results from event
						var $pointsEarned = e.pointsEarned;
						var $isCorrect = e.isCorrect;

						plugin.setCheckMessage( $isCorrect, $pointsEarned );

					});

					break;

				// default standard mode
				case 0:

					plugin.elements.nextButton.show();

					if( !restart ) {

						plugin.finishButtonInit();
						plugin.nextButtonInit();

						// answer check completed
						plugin.element.on( 'quizmaster.answerCheckComplete', function() {

							if( plugin.isLastQuestion() ) {
								plugin.finishQuiz()
							} else {
								plugin.nextQuestion();
							}

						});

					}

					break;
			}

			// maybe hide question position overview
			if ( plugin.config.options.hideQuestionPositionOverview ) {
				plugin.element.find('.quizMaster_question_page').hide();
			}

			// start timer
			plugin.timer.question.start( plugin.getCurrentQuestionId() )

		};

		plugin.startQuizShowQuestion = function() {

			if( plugin.config.mode != 2 ) {

				// get first question object and show
				var $questionList = plugin.elements.questionList.children();
				plugin.setCurrentQuestion( $questionList.eq(0) );
				plugin.showQuestionObject( 'current' );

			}

		};

		plugin.sortableInit = function () {

			plugin.element.find('.qm-sortable').sortable({
				update: function (event, ui) {
					var $p = $(this).parents('.quizMaster_listItem');

					plugin.element.trigger({
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

		plugin.setStatus = function ( statusCode ) {
			plugin.status = statusCode;

			// status change event
			plugin.element.trigger({
				type: 'quizmaster.statusChange',
				status: statusCode,
			});
		}

		plugin.marker = function ( $question, $isCorrect ) {

			$questionInput = plugin.getQuestionInput( $question );
			$questionInput.each( function( index ) {

				$answerChoice = $( this );
				var checked =  $questionInput.eq(index).is(':checked');

				if( checked ) {
					// mark input label
					if( $isCorrect ) {
						$answerChoice.parent().addClass('quizMaster_answerCorrect')
					} else {
						$answerChoice.parent('label').addClass('quizMaster_answerIncorrect')
					}
				}

			})

			// mark entire question correct/incorrect
			if( $isCorrect ) {
				//$question.addClass('quizMaster_answerCorrect')
			} else {
				// $question.addClass('quizMaster_answerIncorrect')
			}

		}

		plugin.getStatus = function () {
			return plugin.status;
		}

		plugin.checkQuizLock = function () {

      plugin.ajax({
          action: 'quizmaster_admin_ajax',
          func: 'quizCheckLock',
          data: {
          	quizId: plugin.config.quizId
          }
      }, function (json) {

				if (json != undefined) {

					var lock = json
          plugin.config.lock = lock

					if( json.lock == true ) {
						plugin.setStatus('locked')
					}

				}
			})
		}

		plugin.init = function( options ) {

			/*
 			 * Set initial status
			 */
			plugin.setStatus('initialized')

			// parse options to plugin.config
			plugin.config = $.extend({

				// default settings
	      bitOptions: {
					cors: true
				},
	      options: {
					catPoints: []
				}

	    }, options );

			// convert the time limit set in seconds to ms
			plugin.timer.convertTimeLimitMs();

			plugin.loadQuizData()
			plugin.checkButtonInit();
			plugin.backButtonInit();
			plugin.startButtonInit();
			plugin.restartButtonInit();
			plugin.questionReviewButtonInit();
			plugin.hintInit();
			plugin.sortableInit();

			/*
			 * Check quiz lock
			 */
			plugin.checkQuizLock()

			/*
  		 * Maybe start quiz or show start page
			 */
			if( plugin.questionCount() == 0 ) {
				// no questions in quiz
				plugin.elements.startButton.hide()
				$('.qm-quiz-start-box').html('No questions in quiz.')
				plugin.element.on( 'quizmaster.quizDataLoaded', plugin.startPageShow )
			} else {
				if( plugin.config.options.isAutostart ) {
					plugin.element.on( 'quizmaster.quizDataLoaded', plugin.startQuiz )
				} else {
					plugin.element.on( 'quizmaster.quizDataLoaded', plugin.startPageShow )
				}
			}

			// quiz setup functions
			plugin.element.on( 'quizmaster.startQuiz', plugin.modeHandler );
			plugin.element.on( 'quizmaster.startQuiz', plugin.startQuizShowQuestion );

			/*
   		 * Event Handlers
			 */

			plugin.element.on( 'quizmaster.questionAnswered', function() {
				plugin.getQuestionInput().attr('disabled', 'disabled')
			});

			// mark questions on answer check completion
			if ( !plugin.config.options.disabledAnswerMark ) {

				plugin.element.on( 'quizmaster.answerCheckComplete', function( e ) {

					$question = e.question;
					$isCorrect = e.isCorrect;
					plugin.marker( $question, $isCorrect );

				});

			}


			// stop timer on question_check status change
			plugin.element.on( 'quizmaster.statusChange', function( e ) {

				var status = e.status;

				if( status == 'check_question' || status == 'check_question_multiple' ) {
					plugin.timer.question.stop();
				}

			});


			// bind questionSolved to questionCheck
			plugin.element.on( 'quizmaster.questionChecked', plugin.questionSolved );

			// bind to quizCompleted event
			plugin.element.on( 'quizmaster.quizCompleted', function() {
				plugin.afterQuizFinish();
			});

			plugin.element.on( 'quizmaster.lastQuestionLoaded', function() {

				if( plugin.config.mode == 0 ) {
					plugin.elements.finishButton.show();
					plugin.elements.checkButton.hide();
					plugin.elements.nextButton.hide();
				}

			});

			// bind to questionAnswered event
			plugin.element.on( 'quizmaster.questionAnswered', function() {
				plugin.checkQuestion()
			});


    };

		// init plugin
		plugin.init( options )

  } // end plugin jQuery plugin

	$.fn.quizmaster = function( options ) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('quizmaster')) {

                // create a new instance of the plugin
                // pass the DOM element and the user-provided options as arguments
                var quizmaster = new $.quizmaster(this, options);

                // in the jQuery version of the element
                // store a reference to the plugin object
                // you can later access the plugin and its methods and properties like
                // element.data('pluginName').publicMethod(arg1, arg2, ... argn) or
                // element.data('pluginName').settings.propertyName
                $(this).data('quizmaster', quizmaster);

            }

        });

    }


});
