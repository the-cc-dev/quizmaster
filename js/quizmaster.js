jQuery(document).ready(function( $ ) {

  // datatables
  $('.quizmaster-info-table').DataTable({
		searching: false,
		paging: false,
		bInfo: false,
		ordering: false,
	});

  $('#quizmaster_score_table').DataTable({
		searching: false
	});

});

/**
 * Source: https://github.com/jfriend00/docReady
 */
(function(funcName, baseObj) {
  // The public function name defaults to window.quizMasterReady
  // but you can pass in your own object and own function name and those will be used
  // if you want to put them in a different namespace
  funcName = funcName || "quizMasterReady";
  baseObj = baseObj || window;
  var readyList = [];
  var readyFired = false;
  var readyEventHandlersInstalled = false;

    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
      if (!readyFired) {
        // this must be set to true before we start calling callbacks
        readyFired = true;
        for (var i = 0; i < readyList.length; i++) {
          // if a callback here happens to add new ready handlers,
          // the quizMasterReady() function will see that it already fired
          // and will schedule the callback to run right after
          // this event loop finishes so all handlers will still execute
          // in order and no new ones will be added to the readyList
          // while we are processing the list
          readyList[i].fn.call(window, readyList[i].ctx);
        }
        // allow any closures held by these functions to free
        readyList = [];
      }
    }

    function readyStateChange() {
      if ( document.readyState === "complete" ) {
        ready();
      }
    }

    // This is the one public interface
    // quizMasterReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete") {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
          // otherwise if we don't have event handlers installed, install them
          if (document.addEventListener) {
            // first choice is DOMContentLoaded event
            document.addEventListener("DOMContentLoaded", ready, false);
            // backup is window load event
            window.addEventListener("load", ready, false);
          } else {
            // must be IE
            document.attachEvent("onreadystatechange", readyStateChange);
            window.attachEvent("onload", ready);
          }
          readyEventHandlersInstalled = true;
        }
    }
})("quizMasterReady", window);

quizmasterQuizRegistry = quizMasterReady(function () {

  var r = window.quizmasterQuizRegistry || [];

  for(var i = 0; i < r.length; i++) {
    jQuery(r[i].id).quizMasterFront(r[i].init);
  }

	return window.quizmasterQuizRegistry;

});

(function ($) {

    /**
     * @memberOf $
     */
    $.quizMasterFront = function (element, options) {

        var $e = $(element);
        var config = options;
				var callbacks = [];
        var plugin = this;
        var results = new Object();
        var catResults = new Object();
        var startTime = 0;
        var currentQuestion = null;
        var quizSolved = [];
        var lastButtonValue = "";
        var inViewQuestions = false;
        var currentPage = 1;

				var registerCallbacks = (function () {

					plugin.methode.ajax({
							action: 'quizmaster_admin_ajax',
							func: 'registerExtensionScriptCallbacks',
							data: {}
						}, function (json) {
							window.quizmasterCallbackRegistry = json;
						}
					);

				});

        var bitOptions = {
            randomAnswer: 0,
            randomQuestion: 0,
            disabledAnswerMark: 0,
            checkBeforeStart: 0,
            preview: 0,
            cors: 0,
            isAddAutomatic: 0,
            quizSummeryHide: 0,
            skipButton: 0,
            reviewQustion: 0,
            autoautoStart: 0,
            forcingQuestionSolve: 0,
            hideQuestionPositionOverview: 0,
            formActivated: 0,
            maxShowQuestion: 0,
            sortCategories: 0
        };

        var quizStatus = {
            isQuizStart: 0,
            isLocked: 0,
            loadLock: 0,
            isPrerequisite: 0,
            isUserStartLocked: 0
        };

        var globalNames = {
            check: '.qm-button-check',
            next: '.qm-button-next',
            questionList: '.quizMaster_questionList',
            skip: 'input[name="skip"]',
            singlePageLeft: 'input[name="quizMaster_pageLeft"]',
            singlePageRight: 'input[name="quizMaster_pageRight"]'
        };

        var globalElements = {
            back: $e.find('input[name="back"]'),
            next: $e.find(globalNames.next),
            quiz: $e.find('.quizMaster_quiz'),
            questionList: $e.find('.quizMaster_list'),
            results: $e.find('.quizMaster_results'),
            quizStartPage: $e.find('.quizMaster_text'),
            timelimit: $e.find('.quizMaster_time_limit'),
            toplistShowInButton: $e.find('.quizMaster_toplistShowInButton'),
            listItems: $()
        };

        var toplistData = {
            token: '',
            isUser: 0
        };

        var formPosConst = {
            START: 0,
            END: 1
        };

        /**
         * @memberOf timelimit
         */
        var timelimit = (function () {
            var _counter = config.timelimit;
            var _intervalId = 0;
            var instance = {};

            instance.stop = function () {
                if (_counter) {
                    window.clearInterval(_intervalId);
                    globalElements.timelimit.hide();
                }
            };

            instance.start = function () {
                if (!_counter)
                    return;

                var x = _counter * 1000;

                var $timeText = globalElements.timelimit.find('span').text(plugin.methode.parseTime(_counter));
                var $timeDiv = globalElements.timelimit.find('.quizMaster_progress');

                globalElements.timelimit.show();

                var beforeTime = +new Date();

                _intervalId = window.setInterval(function () {

                    var diff = (+new Date() - beforeTime);
                    var elapsedTime = x - diff;

                    if (diff >= 500) {
                        $timeText.text(plugin.methode.parseTime(Math.ceil(elapsedTime / 1000)));
                    }

                    $timeDiv.css('width', (elapsedTime / x * 100) + '%');

                    if (elapsedTime <= 0) {
                        instance.stop();
                        plugin.methode.finishQuiz(true);
                    }

                }, 16);
            };

            return instance;

        })();

        /**
         * @memberOf reviewBox
         */
        var reviewBox = new function () {

            var $contain = [], $cursor = [], $list = [], $items = [];
            var x = 0, offset = 0, diff = 0, top = 0, max = 0;
            var itemsStatus = [];

            this.init = function () {
                $contain = $e.find('.quizMaster_reviewQuestion');
                $cursor = $contain.find('div');
                $list = $contain.find('ol');
                $items = $list.children();

                $cursor.mousedown(function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    offset = e.pageY - $cursor.offset().top + top;

                    $(document).bind('mouseup.scrollEvent', endScroll);
                    $(document).bind('mousemove.scrollEvent', moveScroll);

                });

                $items.click(function (e) {
                    plugin.methode.showQuestion($(this).index());
                });

                $e.bind('questionSolved', function (e) {
                    itemsStatus[e.values.index].solved = e.values.solved;
                    setColor(e.values.index);
                });

                $e.bind('changeQuestion', function (e) {
                    $items.removeClass('quizMaster_reviewQuestionTarget');

                    $items.eq(e.values.index).addClass('quizMaster_reviewQuestionTarget');

                    scroll(e.values.index);
                });

                $e.bind('reviewQuestion', function (e) {
                    itemsStatus[e.values.index].review = !itemsStatus[e.values.index].review;
                    setColor(e.values.index);
                });

                $contain.bind('mousewheel DOMMouseScroll', function (e) {
                    e.preventDefault();

                    var ev = e.originalEvent;
                    var w = ev.wheelDelta ? -ev.wheelDelta / 120 : ev.detail / 3;
                    var plus = 20 * w;

                    var x = top - $list.offset().top + plus;

                    if (x > max)
                        x = max;

                    if (x < 0)
                        x = 0;

                    var o = x / diff;

                    $list.attr('style', 'margin-top: ' + (-x) + 'px !important');
                    $cursor.css({top: o});

                    return false;
                });
            };

            this.show = function (save) {
                if (bitOptions.reviewQustion)
                    $contain.parent().show();

                $e.find('.quizMaster_reviewDiv .quizMaster_button2').show();

                if (save)
                    return;

                $list.attr('style', 'margin-top: 0px !important');
                $cursor.css({top: 0});

                var h = $list.outerHeight();
                var c = $contain.height();
                x = c - $cursor.height();
                offset = 0;
                max = h - c;
                diff = max / x;

                this.reset();

                if (h > 100) {
                    $cursor.show();
                }

                top = $cursor.offset().top;
            };

            this.hide = function () {
                $contain.parent().hide();
            };

            this.toggle = function () {
                if (bitOptions.reviewQustion) {
                    $contain.parent().toggle();
                    $items.removeClass('quizMaster_reviewQuestionTarget');
                    $e.find('.quizMaster_reviewDiv .quizMaster_button2').hide();

                    $list.attr('style', 'margin-top: 0px !important');
                    $cursor.css({top: 0});

                    var h = $list.outerHeight();
                    var c = $contain.height();
                    x = c - $cursor.height();
                    offset = 0;
                    max = h - c;
                    diff = max / x;

                    if (h > 100) {
                        $cursor.show();
                    }

                    top = $cursor.offset().top;
                }
            };

            this.reset = function () {
                for (var i = 0, c = $items.length; i < c; i++) {
                    itemsStatus[i] = {};
                }

                $items.removeClass('quizMaster_reviewQuestionTarget').css('background-color', '');
            };

            function scroll(index) {
                var $item = $items.eq(index);
                var iTop = $item.offset().top;
                var cTop = $contain.offset().top;
                var calc = iTop - cTop;

                if ((calc - 4) < 0 || (calc + 32) > 100) {
                    var x = cTop - $items.eq(0).offset().top - (cTop - $list.offset().top) + $item.position().top;

                    if (x > max)
                        x = max;

                    var o = x / diff;

                    $list.attr('style', 'margin-top: ' + (-x) + 'px !important');
                    $cursor.css({top: o});
                }
            }

            function setColor(index) {
                var color = '';
                var itemStatus = itemsStatus[index];

                if (itemStatus.review) {
                    color = '#FFB800';
                } else if (itemStatus.solved) {
                    color = '#6CA54C';
                }

                $items.eq(index).css('background-color', color);
            }

            function moveScroll(e) {
                e.preventDefault();

                var o = e.pageY - offset;

                if (o < 0)
                    o = 0;

                if (o > x)
                    o = x;

                var v = diff * o;

                $list.attr('style', 'margin-top: ' + (-v) + 'px !important');

                $cursor.css({top: o});
            }

            function endScroll(e) {
                e.preventDefault();

                $(document).unbind('.scrollEvent');
            }
        };

        function QuestionTimer() {
            var questionStartTime = 0;
            var currentQuestionId = -1;

            var quizStartTimer = 0;
            var isQuizStart = false;

            this.questionStart = function (questionId) {
                if (currentQuestionId != -1)
                    this.questionStop();

                currentQuestionId = questionId;
                questionStartTime = +new Date();
            };

            this.questionStop = function () {
                if (currentQuestionId == -1)
                    return;

                results[currentQuestionId].time += Math.round((new Date() - questionStartTime) / 1000);

                currentQuestionId = -1;
            };

            this.startQuiz = function () {
                if (isQuizStart)
                  this.stopQuiz();

                quizStartTimer = +new Date();
                isQuizStart = true;
            };

            this.stopQuiz = function () {
                if (!isQuizStart)
                    return;

                results['comp'].quizTime += Math.round((new Date() - quizStartTimer) / 1000);
                isQuizStart = false;
            };

            this.init = function () {

            };

        };

        var questionTimer = new QuestionTimer();

        /**
         * @memberOf checker
         */
        var checker = function (name, data, $question, $questionList) {
            var correct = true;
            var points = 0;
            var isDiffPoints = $.isArray(data.points);
            var statistcAnswerData = {};

            var func = {
                singleMulti: function () {
                    var input = $questionList.find('.quizMaster_questionInput').attr('disabled', 'disabled');
                    var isDiffMode = data.diffMode;

                    $questionList.children().each(function (i) {
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
                                plugin.methode.marker($item, true);
                            }

                        } else {
                            if (checked) {
                                if (!data.disCorrect) {
                                    plugin.methode.marker($item, false);
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
                    var $items = $questionList.children();

                    $items.each(function (i, v) {
                        var $this = $(this);

                        statistcAnswerData[i] = $this.data('pos');

                        if (i == $this.data('pos')) {
                            plugin.methode.marker($this, true);

                            if (isDiffPoints) {
                                points += data.points[i];
                            }
                        } else {
                            plugin.methode.marker($this, false);
                            correct = false;
                        }
                    });

                    $items.children().css({
                        'box-shadow': '0 0',
                        'cursor': 'auto'
                    });

                    $questionList.sortable("destroy");

                    $items.sort(function (a, b) {
                        return $(a).data('pos') > $(b).data('pos') ? 1 : -1;
                    });

                    $questionList.append($items);
                },

                matrix_sort_answer: function () {
                    var $items = $questionList.children();
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
                            plugin.methode.marker($stringUl, true);

                            if (isDiffPoints) {
                                points += data.points[i];
                            }
                        } else {
                            correct = false;
                            plugin.methode.marker($stringUl, false);
                        }

                        matrix[i] = $stringUl;
                    });

                    plugin.methode.resetMatrix($question);

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
                    var $li = $questionList.children();
                    var value = $li.find('.quizMaster_questionInput').attr('disabled', 'disabled').val();

                    if ($.inArray($.trim(value).toLowerCase(), data.correct) >= 0) {
                        plugin.methode.marker($li, true);
                    } else {
                        plugin.methode.marker($li, false);
                        correct = false;
                    }
                },

                cloze_answer: function () {
                    $questionList.find('.quizMaster_cloze').each(function (i, v) {
                        var $this = $(this);
                        var cloze = $this.children();
                        var input = cloze.eq(0);
                        var span = cloze.eq(1);
                        var inputText = plugin.methode.cleanupCurlyQuotes(input.val());

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
                    var $input = $questionList.find('.quizMaster_questionInput').attr('disabled', 'disabled');
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

        /**
         *  @memberOf formClass
         */
        var formClass = new function () {
            var funcs = {
                isEmpty: function (str) {
                    str = $.trim(str);
                    return (!str || 0 === str.length);
                }
            };

            var typeConst = {
                TEXT: 0,
                TEXTAREA: 1,
                NUMBER: 2,
                CHECKBOX: 3,
                EMAIL: 4,
                YES_NO: 5,
                DATE: 6,
                SELECT: 7,
                RADIO: 8
            };

            this.checkForm = function () {
                var check = true;

                $e.find('.quizMaster_forms input, .quizMaster_forms textarea, .quizMaster_forms .quizMaster_formFields, .quizMaster_forms select').each(function () {
                    var $this = $(this);
                    var isRequired = $this.data('required') == 1;
                    var type = $this.data('type');
                    var test = true;
                    var value = $.trim($this.val());

                    switch (type) {
                        case typeConst.TEXT:
                        case typeConst.TEXTAREA:
                        case typeConst.SELECT:
                            if (isRequired)
                                test = !funcs.isEmpty(value);

                            break;
                        case typeConst.NUMBER:
                            if (isRequired || !funcs.isEmpty(value))
                                test = !funcs.isEmpty(value) && !isNaN(value);

                            break;
                        case typeConst.EMAIL:
                            if (isRequired || !funcs.isEmpty(value))
                                test = !funcs.isEmpty(value) && new RegExp(/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/)
                                        .test(value);

                            break;
                        case typeConst.CHECKBOX:
                            if (isRequired)
                                test = $this.is(':checked');

                            break;
                        case typeConst.YES_NO:
                        case typeConst.RADIO:
                            if (isRequired)
                                test = $this.find('input[type="radio"]:checked').val() !== undefined;
                            break;
                        case typeConst.DATE:
                            var num = 0, co = 0;

                            $this.find('select').each(function () {
                                num++;
                                co += funcs.isEmpty($(this).val()) ? 0 : 1;
                            });

                            if (isRequired || co > 0)
                                test = num == co;

                            break;
                    }

                    if (test) {
                        $this.siblings('.quizMaster_invalidate').hide();
                    } else {
                        check = false;
                        $this.siblings('.quizMaster_invalidate').show();
                    }

                });

                return check;
            };

            this.getFormData = function () {
                var data = {};

                $e.find('.quizMaster_forms input, .quizMaster_forms textarea, .quizMaster_forms .quizMaster_formFields, .quizMaster_forms select').each(function () {
                    var $this = $(this);
                    var id = $this.data('form_id');
                    var type = $this.data('type');

                    switch (type) {
                        case typeConst.TEXT:
                        case typeConst.TEXTAREA:
                        case typeConst.SELECT:
                        case typeConst.NUMBER:
                        case typeConst.EMAIL:
                            data[id] = $this.val();
                            break;
                        case typeConst.CHECKBOX:
                            data[id] = $this.is(':checked') ? 1 : 0;
                            break;
                        case typeConst.YES_NO:
                        case typeConst.RADIO:
                            data[id] = $this.find('input[type="radio"]:checked').val();
                            break;
                        case typeConst.DATE:
                            data[id] = {
                                day: $this.find('select[name="quizMaster_field_' + id + '_day"]').val(),
                                month: $this.find('select[name="quizMaster_field_' + id + '_month"]').val(),
                                year: $this.find('select[name="quizMaster_field_' + id + '_year"]').val()
                            };
                            break;
                    }
                });

                return data;
            };
        };

        var fetchAllAnswerData = function (resultData) {
            $e.find('.quizMaster_questionList').each(function () {
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

                resultData[questionId]['data'] = data;

            });
        };

				plugin.startQuiz = function () {
					plugin.methode.startQuiz();
				};

				plugin.blockQuiz = function () {
					return quizStatus.isBlocked = true;
				};

				plugin.unblockQuiz = function () {
					return quizStatus.isBlocked = false;
				};

				plugin.lockQuiz = function () {
					return quizStatus.isLocked = true;
				};

				plugin.setQuizStatus = function ( prop, val ) {
					quizStatus[ prop ] = val
					return quizStatus;
				};

				plugin.getQuizStatus = function () {
					return quizStatus;
				};

				plugin.hideQuizStart = function () {

					globalElements.quizStartPage.hide();
					plugin.methode.startQuiz();

				};

        plugin.methode = {

						// public method used for extensions to hook into events
						registerAction: function ( action, object, func ) {

							var a = {
								object: object,
								func: func,
							}

							if( callbacks.hasOwnProperty( action ) == false ){
								callbacks[action] = []
							}

							callbacks[action].push( a )

						},



            /**
             * @memberOf plugin.methode
             */

            parseBitOptions: function () {

                if (config.bo) {
                    bitOptions.randomAnswer = config.bo & (1 << 0);
                    bitOptions.randomQuestion = config.bo & (1 << 1);
                    bitOptions.disabledAnswerMark = config.bo & (1 << 2);
                    bitOptions.checkBeforeStart = config.bo & (1 << 3);
                    bitOptions.preview = config.bo & (1 << 4);
                    bitOptions.isAddAutomatic = config.bo & (1 << 6);
                    bitOptions.reviewQustion = config.bo & ( 1 << 7);
                    bitOptions.quizSummeryHide = config.bo & (1 << 8);
                    bitOptions.skipButton = config.bo & (1 << 9);
                    bitOptions.autoStart = config.bo & (1 << 10);
                    bitOptions.forcingQuestionSolve = config.bo & (1 << 11);
                    bitOptions.hideQuestionPositionOverview = config.bo & (1 << 12);
                    bitOptions.formActivated = config.bo & (1 << 13);
                    bitOptions.maxShowQuestion = config.bo & (1 << 14);
                    bitOptions.sortCategories = config.bo & (1 << 15);

                    var cors = config.bo & (1 << 5);

                    if (cors && jQuery.support != undefined && jQuery.support.cors != undefined && jQuery.support.cors == false) {
                        bitOptions.cors = cors;
                    }
                }
            },

            setClozeStyle: function () {
                $e.find('.quizMaster_cloze input').each(function () {
                    var $this = $(this);
                    var word = "";
                    var wordLen = $this.data('wordlen');

                    for (var i = 0; i < wordLen; i++)
                        word += "w";

                    var clone = $(document.createElement("span"))
                        .css('visibility', 'hidden')
                        .text(word)
                        .appendTo($('body'));

                    var width = clone.width();

                    clone.remove();

                    $this.width(width + 5);
                });
            },

            parseTime: function (sec) {
                var seconds = parseInt(sec % 60);
                var minutes = parseInt((sec / 60) % 60);
                var hours = parseInt((sec / 3600) % 24);

                seconds = (seconds > 9 ? '' : '0') + seconds;
                minutes = (minutes > 9 ? '' : '0') + minutes;
                hours = (hours > 9 ? '' : '0') + hours;

                return hours + ':' + minutes + ':' + seconds;
            },

            cleanupCurlyQuotes: function (str) {
                str = str.replace(/\u2018/, "'");
                str = str.replace(/\u2019/, "'");

                str = str.replace(/\u201C/, '"');
                str = str.replace(/\u201D/, '"');

                return $.trim(str).toLowerCase();
            },

            resetMatrix: function (selector) {
                selector.each(function () {
                    var $this = $(this);
                    var $list = $this.find('.quizMaster_sortStringList');

                    $this.find('.quizMaster_sortStringItem').each(function () {
                        $list.append($(this));
                    });
                });
            },

            marker: function (e, correct) {
                if (!bitOptions.disabledAnswerMark) {
                    if (correct) {
                        e.addClass('quizMaster_answerCorrect');
                    } else {
                        e.addClass('quizMaster_answerIncorrect');
                    }
                }

            },

            startQuiz: function (loadData) {

              if ( quizStatus.loadLock ) {
                quizStatus.isQuizStart = 1;
                return;
              }

              quizStatus.isQuizStart = 0;

							if ( quizStatus.isBlocked ) {
                globalElements.quizStartPage.hide();
                return;
              }

              if ( quizStatus.isLocked ) {
                globalElements.quizStartPage.hide();
                $e.find('.quizMaster_lock').show();
                return;
              }

              if ( quizStatus.isPrerequisite ) {
                globalElements.quizStartPage.hide();
                $e.find('.quizMaster_prerequisite').show();
                return;
              }

                if ( quizStatus.isUserStartLocked ) {
                  globalElements.quizStartPage.hide();
                  $e.find('.quizMaster_startOnlyRegisteredUser').show();
                  return;
                }

                if (bitOptions.maxShowQuestion && !loadData) {

										globalElements.quizStartPage.hide();
                    $e.find('.quizMaster_loadQuiz').show();
                    plugin.methode.loadQuizDataAjax(true);
                    return;

                }

                if (bitOptions.formActivated && config.formPos == formPosConst.START) {
	                if (!formClass.checkForm())
	                  return;
                }

                plugin.methode.loadQuizData();

                if (bitOptions.randomQuestion) {
                  plugin.methode.random(globalElements.questionList);
                }

                if (bitOptions.randomAnswer) {
                  plugin.methode.random($e.find(globalNames.questionList));
                }

                if (bitOptions.sortCategories) {
                    plugin.methode.sortCategories();
                }

                plugin.methode.random($e.find('.quizMaster_sortStringList'));
                plugin.methode.random($e.find('[data-type="sort_answer"]'));

                $e.find('.quizMaster_listItem').each(function (i, v) {
                    var $this = $(this);
                    $this.find('.quizMaster_question_page span:eq(0)').text(i + 1);
                    $this.find('> h5 span').text(i + 1);

                    $this.find('.quizMaster_questionListItem').each(function (i, v) {
                        $(this).find('> span:not(.quizMaster_cloze)').text(i + 1 + '. ');
                    });
                });

                globalElements.next = $e.find(globalNames.next);

                switch (config.mode) {
                    case 3:
                        $e.find('input[name="checkSingle"]').show();
                        break;
                    case 2:
                        $e.find(globalNames.check).show();

                        if (!bitOptions.skipButton && bitOptions.reviewQustion)
                            $e.find(globalNames.skip).show();

                        break;
                    case 1:
                        $e.find('input[name="back"]').slice(1).show();
                    case 0:
                        globalElements.next.show();
                        break;
                }

                if (bitOptions.hideQuestionPositionOverview || config.mode == 3)
                    $e.find('.quizMaster_question_page').hide();

                var $listItem = globalElements.questionList.children();

                globalElements.listItems = $e.find('.quizMaster_list > li');

                if (config.mode == 3) {
                    plugin.methode.showSinglePage(0);
                } else {
                    currentQuestion = $listItem.eq(0).show();

                    var questionId = currentQuestion.find(globalNames.questionList).data('question_id');
                    questionTimer.questionStart(questionId);
                }

                questionTimer.startQuiz();

                $e.find('.quizMaster_sortable').parents('ul').sortable({
                    update: function (event, ui) {
                        var $p = $(this).parents('.quizMaster_listItem');

                        $e.trigger({
                            type: 'questionSolved',
                            values: {
                                item: $p,
                                index: $p.index(),
                                solved: true
                            }
                        });
                    }
                }).disableSelection();

                $e.find('.quizMaster_sortStringList, .quizMaster_maxtrixSortCriterion').sortable({
                    connectWith: '.quizMaster_maxtrixSortCriterion:not(:has(li)), .quizMaster_sortStringList',
                    placeholder: 'quizMaster_placehold',
                    update: function (event, ui) {
                        var $p = $(this).parents('.quizMaster_listItem');

                        $e.trigger({
                            type: 'questionSolved',
                            values: {
                                item: $p,
                                index: $p.index(),
                                solved: true
                            }
                        });
                    }
                }).disableSelection();

                quizSolved = [];

                timelimit.start();

                startTime = +new Date();

                results = {
                    comp: {
                        points: 0,
                        correctQuestions: 0,
                        quizTime: 0
                    }
                };

                $e.find('.quizMaster_questionList').each(function () {
                    var questionId = $(this).data('question_id');

                    results[questionId] = {
                        time: 0,
                        solved: 0
                    };
                });

                catResults = {};

                $.each(options.catPoints, function (i, v) {
                    catResults[i] = 0;
                });

                globalElements.quizStartPage.hide();
                $e.find('.quizMaster_loadQuiz').hide();
                globalElements.quiz.show();
                reviewBox.show();

                if (config.mode != 3) {
                    $e.trigger({
                        type: 'changeQuestion',
                        values: {
                            item: currentQuestion,
                            index: currentQuestion.index()
                        }
                    });
                }
            },

            showSingleQuestion: function (question) {
                var page = question ? Math.ceil(question / config.qpp) : 1;
                this.showSinglePage(page);
            },

            showSinglePage: function (page) {
                $listItem = globalElements.questionList.children().hide();

                if (!config.qpp) {
                    $listItem.show();

                    return;
                }

                page = page ? +page : 1;
                var maxPage = Math.ceil($e.find('.quizMaster_list > li').length / config.qpp);

                if (page > maxPage)
                    return;

                var pl = $e.find(globalNames.singlePageLeft).hide();
                var pr = $e.find(globalNames.singlePageRight).hide();
                var cs = $e.find('input[name="checkSingle"]').hide();

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
                plugin.methode.scrollTo(globalElements.quiz);
            },

            nextQuestion: function () {
              this.showQuestionObject(currentQuestion.next());
            },

            prevQuestion: function () {
              this.showQuestionObject(currentQuestion.prev());
            },

            showQuestion: function (index) {
                var $element = globalElements.listItems.eq(index);

                if (config.mode == 3 || inViewQuestions) {
                    if (config.qpp) {
                      plugin.methode.showSingleQuestion(index + 1);
                    }
                    plugin.methode.scrollTo($element, 1);
                    questionTimer.startQuiz();
                    return;
                }

                this.showQuestionObject($element);
            },

						questionCount: function () {

							console.log( $e )

							return $e.find('.quizMaster_listItem').length;
						},

            showQuestionObject: function (obj) {
                if (!obj.length && bitOptions.forcingQuestionSolve && bitOptions.quizSummeryHide && bitOptions.reviewQustion) {
                    for (var i = 0, c = $e.find('.quizMaster_listItem').length; i < c; i++) {
                        if (!quizSolved[i]) {
                            alert(QuizMasterGlobal.questionsNotSolved);
                            return false;
                        }
                    }
                }

                currentQuestion.hide();
                currentQuestion = obj.show();

								//Change last name
								if( plugin.methode.questionCount() == currentQuestion.index() +1 ) {
									var $lastButton = globalElements.next.last();
									lastButtonValue = $lastButton.val();
									$lastButton.val(config.lbn);
								}

                plugin.methode.scrollTo(globalElements.quiz);

                $e.trigger({
                    type: 'changeQuestion',
                    values: {
                        item: currentQuestion,
                        index: currentQuestion.index()
                    }
                });

                if (!currentQuestion.length) {
                    plugin.methode.showQuizSummary();
                } else {
                    var questionId = currentQuestion.find(globalNames.questionList).data('question_id');
                    questionTimer.questionStart(questionId);
                }
            },

            skipQuestion: function () {
                $e.trigger({
                    type: 'skipQuestion',
                    values: {
                        item: currentQuestion,
                        index: currentQuestion.index()
                    }
                });

                plugin.methode.nextQuestion();
            },

            reviewQuestion: function () {
                $e.trigger({
                    type: 'reviewQuestion',
                    values: {
                        item: currentQuestion,
                        index: currentQuestion.index()
                    }
                });
            },

            showQuizSummary: function () {
                questionTimer.questionStop();
                questionTimer.stopQuiz();

                if (bitOptions.quizSummeryHide || !bitOptions.reviewQustion) {
                    if (bitOptions.formActivated && config.formPos == formPosConst.END) {
                        reviewBox.hide();
                        globalElements.quiz.hide();
                        plugin.methode.scrollTo($e.find('.quizMaster_infopage').show());
                    } else {
                        plugin.methode.finishQuiz();
                    }

                    return;
                }

                var quizSummary = $e.find('.quizMaster_checkPage');

                quizSummary.find('ol:eq(0)').empty()
                    .append($e.find('.quizMaster_reviewQuestion ol li').clone().removeClass('quizMaster_reviewQuestionTarget'))
                    .children().click(function (e) {
                        quizSummary.hide();
                        globalElements.quiz.show();
                        reviewBox.show(true);

                        plugin.methode.showQuestion($(this).index());
                    });

                var cSolved = 0;

                for (var i = 0, c = quizSolved.length; i < c; i++) {
                    if (quizSolved[i]) {
                        cSolved++;
                    }
                }

                quizSummary.find('span:eq(0)').text(cSolved);

                reviewBox.hide();
                globalElements.quiz.hide();

                quizSummary.show();

                plugin.methode.scrollTo(quizSummary);
            },

            finishQuiz: function (timeover) {
                questionTimer.questionStop();
                questionTimer.stopQuiz();
                timelimit.stop();

                var time = (+new Date() - startTime) / 1000;
                time = (config.timelimit && time > config.timelimit) ? config.timelimit : time;

                $e.find('.quizMaster_quiz_time span').text(plugin.methode.parseTime(time));

                if (timeover) {
                    globalElements.results.find('.quizMaster_time_limit_expired').show();
                }

                plugin.methode.checkQuestion(globalElements.questionList.children(), true);
                $e.find('.quizMaster_correct_answer').text(results.comp.correctQuestions);

                results.comp.result = Math.round(results.comp.points / config.globalPoints * 100 * 100) / 100;
                results.comp.solved = 0;
                var $pointFields = $e.find('.quizMaster_points span');

                $pointFields.eq(0).text(results.comp.points);
                $pointFields.eq(1).text(config.globalPoints);
                $pointFields.eq(2).text(results.comp.result + '%');

                var $resultText = $e.find('.quizMaster_resultsList > li').eq(0);

                var formData = formClass.getFormData();

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

                plugin.methode.setAverageResult(results.comp.result, false);

                this.setCategoryOverview();

                plugin.methode.sendCompletedQuiz();

                reviewBox.hide();

                $e.find('.quizMaster_checkPage, .quizMaster_infopage').hide();
                globalElements.quiz.hide();
                globalElements.results.show();
                plugin.methode.scrollTo(globalElements.results);
            },

            setCategoryOverview: function () {
                results.comp.cats = {};

                $e.find('.quizMaster_catOverview li').each(function () {
                    var $this = $(this);
                    var catId = $this.data('category_id');

                    if (config.catPoints[catId] === undefined) {
                        $this.hide();
                        return true;
                    }

                    var r = Math.round(catResults[catId] / config.catPoints[catId] * 100 * 100) / 100;

                    results.comp.cats[catId] = r;

                    $this.find('.quizMaster_catPercent').text(r + '%');

                    $this.show();
                });
            },

            questionSolved: function (e) {
                quizSolved[e.values.index] = e.values.solved;

                var $questionList = e.values.item.find(globalNames.questionList);
                var data = config.json[$questionList.data('question_id')];
                results[data.id].solved = Number(e.values.fake ? results[data.id].solved : e.values.solved);
            },

            sendCompletedQuiz: function () {
                if (bitOptions.preview)
                    return;

                fetchAllAnswerData(results);

                var formData = formClass.getFormData();

                plugin.methode.ajax({
                    action: 'quizmaster_admin_ajax',
                    func: 'completedQuiz',
                    data: {
                        quizId: config.quizId,
                        results: results,
                        forms: formData
                    }
                });
            },

            showQustionList: function () {
                inViewQuestions = !inViewQuestions;
                globalElements.toplistShowInButton.hide();
                globalElements.quiz.toggle();
                $e.find('.quizMaster_QuestionButton').hide();
                globalElements.questionList.children().show();
                reviewBox.toggle();

                $e.find('.quizMaster_question_page').hide();
            },

            random: function (group) {
                group.each(function () {
                    var e = $(this).children().get().sort(function () {
                        return Math.round(Math.random()) - 0.5;
                    });

                    $(e).appendTo(e[0].parentNode);
                });
            },

            sortCategories: function () {
                var e = $('.quizMaster_list').children().get().sort(function (a, b) {
                    var aQuestionId = $(a).find('.quizMaster_questionList').data('question_id');
                    var bQuestionId = $(b).find('.quizMaster_questionList').data('question_id');

                    return config.json[aQuestionId].catId - config.json[bQuestionId].catId;
                });

                $(e).appendTo(e[0].parentNode);
            },

            restartQuiz: function () {
                globalElements.results.hide();
                globalElements.quizStartPage.show();
                globalElements.questionList.children().hide();
                globalElements.toplistShowInButton.hide();
                reviewBox.hide();

                $e.find('.quizMaster_questionInput, .quizMaster_cloze input').removeAttr('disabled').removeAttr('checked')
                    .css('background-color', '');

                $e.find('.quizMaster_questionListItem input[type="text"]').val('');

                $e.find('.quizMaster_answerCorrect, .quizMaster_answerIncorrect').removeClass('quizMaster_answerCorrect quizMaster_answerIncorrect');

                $e.find('.quizMaster_listItem').data('check', false);
                $e.find('.quizMaster_response').hide().children().hide();

                plugin.methode.resetMatrix($e.find('.quizMaster_listItem'));
                $e.find('.quizMaster_sortStringItem, .quizMaster_sortable').removeAttr('style');
                $e.find('.quizMaster_clozeCorrect, .quizMaster_QuestionButton, .quizMaster_resultsList > li').hide();

                $e.find('.quizMaster_question_page, input[name="tip"]').show();
                $e.find('.quizMaster_resultForm').text('').hide();

                globalElements.results.find('.quizMaster_time_limit_expired').hide();

                inViewQuestions = false;
            },

            checkQuestion: function (list, endCheck) {
                list = (list == undefined) ? currentQuestion : list;

                list.each(function () {
                    var $this = $(this);
                    var $questionList = $this.find(globalNames.questionList);
                    var data = config.json[$questionList.data('question_id')];
                    var name = data.type;

                    questionTimer.questionStop();

                    if ($this.data('check')) {
                        return true;
                    }

                    if (data.type == 'single' || data.type == 'multiple') {
                        name = 'singleMulti';
                    }

                    var result = checker(name, data, $this, $questionList);

                    $this.find('.quizMaster_response').show();
                    $this.find(globalNames.check).hide();
                    $this.find(globalNames.skip).hide();
                    $this.find(globalNames.next).show();

                    results[data.id].points = result.p;
                    results[data.id].correct = Number(result.c);
                    results[data.id].data = result.s;

                    results['comp'].points += result.p;

                    catResults[data.catId] += result.p;

                    if (result.c) {
                        $this.find('.quizMaster_correct').show();
                        results['comp'].correctQuestions += 1;
                    } else {
                        $this.find('.quizMaster_incorrect').show();
                    }

                    $this.find('.quizMaster_responsePoints').text(result.p);

                    $this.data('check', true);

                    if (!endCheck)
                        $e.trigger({
                            type: 'questionSolved',
                            values: {
                                item: $this,
                                index: $this.index(),
                                solved: true,
                                fake: true
                            }
                        });
                });
            },

            showTip: function ( event ) {

              var $this = $(this);
              var id = currentQuestion.find(globalNames.questionList).data('question_id');

							// get tip div
							var $tip = currentQuestion.find('.quizMaster_tipp').html();
							$tipModal = $('.qm-hint-modal');

							// check if element is Visible
							var isVisible = $tipModal.is(':visible');

							// show or hide the tip
							if (isVisible === true) {

							   $tipModal.hide();

							} else {

								// populate modal with current question tip
								$tipModal.html( $tip )

								// adjust modal position
								$tipModal.css({
									position: "absolute",
									left: $this.position().left + "px",
									top: ($this.position().top + $this.outerHeight()) + "px",
									display: "block",
								});

								// record use of tip
                results[id].tip = 1;

							}

            },

            ajax: function (data, success, dataType) {
                dataType = dataType || 'json';

                if (bitOptions.cors) {
                    jQuery.support.cors = true;
                }

                $.post(QuizMasterGlobal.ajaxurl, data, success, dataType);

                if (bitOptions.cors) {
                    jQuery.support.cors = false;
                }
            },

            checkQuizLock: function () {

                quizStatus.loadLock = 1;

                plugin.methode.ajax({
                    action: 'quizmaster_admin_ajax',
                    func: 'quizCheckLock',
                    data: {
                      quizId: config.quizId
                    }
                }, function (json) {

									// run callback hooks
									var quizLockCallbacks = callbacks['checkQuizLock'];
									$.each( quizLockCallbacks, function( index, value ) {

										// find object
										var fn = window[value.object][value.func];

										// is object a function?
										if (typeof fn === "function") {
											var quizMasterFront = this;
											fn( json, quizStatus, globalElements );
										}

									});

                  if (json.lock != undefined) {
                    quizStatus.isLocked = json.lock.is;

                    if ( json.lock.pre ) {
                      $e.find('input[name="restartQuiz"]').hide();
                    }
                  }

                  if (json.prerequisite != undefined) {
                    quizStatus.isPrerequisite = 1;
                    $e.find('.quizMaster_prerequisite span').text(json.prerequisite);
                  }

                  if (json.startUserLock != undefined) {
                    quizStatus.isUserStartLocked = json.startUserLock;
                  }

                  quizStatus.loadLock = 0;

                  if ( quizStatus.isQuizStart ) {
                    plugin.methode.startQuiz();
                  }

                });
            },

            loadQuizData: function () {
                plugin.methode.ajax({
                    action: 'quizmaster_admin_ajax',
                    func: 'loadQuizData',
                    data: {
                        quizId: config.quizId
                    }
                }, function (json) {

                    if (json.averageResult != undefined) {
                        plugin.methode.setAverageResult(json.averageResult, true);
                    }

                });

            },

            setAverageResult: function (p, g) {
                var v = $e.find('.quizMaster_resultValue:eq(' + (g ? 0 : 1) + ') > * ');

                v.eq(1).text(p + '%');
                v.eq(0).css('width', (240 * p / 100) + 'px');
            },

            scrollTo: function (e, h) {
                var x = e.offset().top - 100;

                if (h || (window.pageYOffset || document.body.scrollTop) > x) {
                    $('html,body').animate({scrollTop: x}, 300);
                }
            },

            registerSolved: function () {
                $e.find('.quizMaster_questionInput[type="text"]').change(function (e) {
                    var $this = $(this);
                    var $p = $this.parents('.quizMaster_listItem');
                    var s = false;

                    if ($this.val() != '') {
                        s = true;
                    }

                    $e.trigger({
                        type: 'questionSolved',
                        values: {
                            item: $p,
                            index: $p.index(),
                            solved: s
                        }
                    });
                });

                $e.find('.quizMaster_questionList[data-type="single"] .quizMaster_questionInput, .quizMaster_questionList[data-type="assessment_answer"] .quizMaster_questionInput').change(function (e) {
                    var $this = $(this);
                    var $p = $this.parents('.quizMaster_listItem');
                    var s = this.checked;

                    $e.trigger({
                        type: 'questionSolved',
                        values: {
                            item: $p,
                            index: $p.index(),
                            solved: s
                        }
                    });
                });

                $e.find('.quizMaster_cloze input').change(function () {
                    var $this = $(this);
                    var $p = $this.parents('.quizMaster_listItem');
                    var s = true;

                    $p.find('.quizMaster_cloze input').each(function () {
                        if ($(this).val() == '') {
                            s = false;
                            return false;
                        }
                    });

                    $e.trigger({
                        type: 'questionSolved',
                        values: {
                            item: $p,
                            index: $p.index(),
                            solved: s
                        }
                    });
                });

                $e.find('.quizMaster_questionList[data-type="multiple"] .quizMaster_questionInput').change(function (e) {
                    var $this = $(this);
                    var $p = $this.parents('.quizMaster_listItem');
                    var c = 0;

                    $p.find('.quizMaster_questionList[data-type="multiple"] .quizMaster_questionInput').each(function (e) {
                        if (this.checked)
                            c++;
                    });

                    $e.trigger({
                        type: 'questionSolved',
                        values: {
                            item: $p,
                            index: $p.index(),
                            solved: (c) ? true : false
                        }
                    });

                });
            },

            loadQuizDataAjax: function (quizStart) {
                plugin.methode.ajax({
                    action: 'quizmaster_admin_ajax',
                    func: 'quizLoadData',
                    data: {
                        quizId: config.quizId
                    }
                }, function (json) {

                    config.globalPoints = json.globalPoints;
                    config.catPoints = json.catPoints;
                    config.json = json.json;

                    globalElements.quiz.remove();

                    $e.find('.quizMaster_quizAnker').after(json.content);

                    //Reinit globalElements
                    globalElements = {
                        back: $e.find('input[name="back"]'),
                        next: $e.find(globalNames.next),
                        quiz: $e.find('.quizMaster_quiz'),
                        questionList: $e.find('.quizMaster_list'),
                        results: $e.find('.quizMaster_results'),
                        quizStartPage: $e.find('.quizMaster_text'),
                        timelimit: $e.find('.quizMaster_time_limit'),
                        toplistShowInButton: $e.find('.quizMaster_toplistShowInButton'),
                        listItems: $()
                    };

                    plugin.methode.initQuiz();

                    if (quizStart)
                      plugin.methode.startQuiz(true);

                });
            },

            initQuiz: function () {
                plugin.methode.setClozeStyle();
                plugin.methode.registerSolved();

                globalElements.next.click(function () {
                    if (bitOptions.forcingQuestionSolve && !quizSolved[currentQuestion.index()]
                        && (bitOptions.quizSummeryHide || !bitOptions.reviewQustion)) {

                        alert(QuizMasterGlobal.questionNotSolved);
                        return false;
                    }

                    plugin.methode.nextQuestion();
                });

                globalElements.back.click(function () {
                    plugin.methode.prevQuestion();
                });

                $e.find(globalNames.check).click(function () {
                    if (bitOptions.forcingQuestionSolve && !quizSolved[currentQuestion.index()]
                        && (bitOptions.quizSummeryHide || !bitOptions.reviewQustion)) {

                        alert(QuizMasterGlobal.questionNotSolved);
                        return false;
                    }

                    plugin.methode.checkQuestion();
                });

                $e.find('input[name="checkSingle"]').click(function () {
                    if (bitOptions.forcingQuestionSolve && (bitOptions.quizSummeryHide || !bitOptions.reviewQustion)) {
                        for (var i = 0, c = $e.find('.quizMaster_listItem').length; i < c; i++) {
                            if (!quizSolved[i]) {
                                alert(QuizMasterGlobal.questionsNotSolved);
                                return false;
                            }
                        }
                    }

                    plugin.methode.showQuizSummary();
                });

                $e.find('.qm-hint-trigger').click(plugin.methode.showTip);
                $e.find('input[name="skip"]').click(plugin.methode.skipQuestion);

                $e.find('input[name="quizMaster_pageLeft"]').click(function () {
                    plugin.methode.showSinglePage(currentPage - 1);
                });

                $e.find('input[name="quizMaster_pageRight"]').click(function () {
                    plugin.methode.showSinglePage(currentPage + 1);
                });
            }
        };

        /**
         * @memberOf plugin
         */
        plugin.preInit = function () {

            plugin.methode.parseBitOptions();
            reviewBox.init();

            $e.find('input[name="startQuiz"]').click(function () {
                plugin.methode.startQuiz();
                return false;
            });

						// check quiz lock
            plugin.methode.checkQuizLock();

            $e.find('input[name="reShowQuestion"]').click(function () {
                plugin.methode.showQustionList();
            });

            $e.find('input[name="restartQuiz"]').click(function () {
                plugin.methode.restartQuiz();
            });

            $e.find('input[name="review"]').click(plugin.methode.reviewQuestion);

            $e.find('input[name="quizSummary"]').click(plugin.methode.showQuizSummary);

            $e.find('input[name="endQuizSummary"]').click(function () {
                if (bitOptions.forcingQuestionSolve) {
                    for (var i = 0, c = $e.find('.quizMaster_listItem').length; i < c; i++) {
                        if (!quizSolved[i]) {
                            alert(QuizMasterGlobal.questionsNotSolved);
                            return false;
                        }
                    }
                }

                if (bitOptions.formActivated && config.formPos == formPosConst.END && !formClass.checkForm())
                    return;

                plugin.methode.finishQuiz();
            });

            $e.find('input[name="endInfopage"]').click(function () {
                if (formClass.checkForm())
                    plugin.methode.finishQuiz();
            });

            $e.find('input[name="showToplist"]').click(function () {
                globalElements.quiz.hide();
                globalElements.toplistShowInButton.toggle();
            });

            $e.bind('questionSolved', plugin.methode.questionSolved);

            if (!bitOptions.maxShowQuestion) {
                plugin.methode.initQuiz();
            }

            if (bitOptions.autoStart)
                plugin.methode.startQuiz();
        };

        plugin.preInit();
    };

    $.fn.quizMasterFront = function (options) {
        return this.each(function () {
            if (undefined == $(this).data('quizMasterFront')) {
                $(this).data('quizMasterFront', new $.quizMasterFront(this, options));
            }
        });
    };

})(jQuery);
