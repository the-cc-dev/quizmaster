=== QuizMaster ===
Contributors: goldhat
Donate link: https://goldhat.ca/donate/
Tags: quiz, test, answer, question, learning, assessment
Requires at least: 4.0
Tested up to: 4.3
Stable tag: 0.4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best quiz system plugin for WordPress.

== Description ==

The best quiz system plugin for WordPress. Highly extendable and developer-friendly. Requires FieldMaster (Free) or ACF Pro. Forked in 2016 from WP Pro Quiz, a free quiz plugin developed by Julius Fischer (http://www.it-gecko.de/).

= Features =
* Single Choice Question Type
* Multiple Choice Question Type
* Sorting Choice Question Type
* Free Choice Question Type
* Fill in the Blank Question Type
* Time Limit
* Randomize Answers
* Randomize Questions
* Correct or incorrect response message for all questions
* Different points for each answer
* Result text with gradations
* Preview-function
* Statistics
* Quiz requirements
* Hints
* E-mail notification
* Category support
* Quiz summary

= QuizMaster Wiki =
https://github.com/goldhat/quizmaster/wiki

= Support =
Visit https://goldhat.ca/plugins/quizmaster

== Installation ==

1. Upload the "quizmaster" folder to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.0.2 =

Added teachers extension with capability to add quizzes and questions.

== Upgrade Notice ==

N/A

== FAQ ==

= Why does the plugin require a fields API? =

We originally included ACF Pro because of it's great UX fields and because it allows us to offer great support for extending the plugin. Developers are able to easily add fields into the UX and there is great consistency in the interface by using ACF. However we were not able to strike a deal with the ACF developer to enable us to embed ACF Pro into the plugin, and QuizMaster already relied on certain pro features including repeater field and options pages. Thus we invented the new fields plugin FieldMaster which is a fork of ACF Pro, and available for free. We recommend using FieldMaster unless you already have ACF Pro installed, because we are bundling additional ACF integration plugins that provide an even better UX, such as collapsing repeater fields.

== Screenshots ==

1. Test

== Credits ==

This plugin would not be possible with the work of Julius Fischer, the developer of WP Pro Quiz.
