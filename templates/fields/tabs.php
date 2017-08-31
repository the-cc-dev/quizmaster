<div class="qm-tabs">

	<ul>

		<?php foreach( $tabs as $tab ) : ?>

			<li data-tab-key="<?php print $tab['key']; ?>">
				<?php print $tab['label']; ?>
			</li>

		<?php endforeach; ?>

	</ul>

</div>

<style>

#quizmaster-quiz-metabox > .handlediv,
#quizmaster-quiz-metabox > .hndle {
	display: none;
}

#quizmaster-quiz-metabox.postbox {
	border: none;
	background: none;
}
#quizmaster-quiz-metabox.postbox .inside {
	padding: 0 0 12px 0;
}


	.qm-tabs {
		float: left;
		display: table;
		min-width: 140px;
		max-width: 180px;
		display: block;
		overflow: auto;
	}

	.qm-tabs ul {
		margin: 0;
		padding: 0;
	}

	.qm-tabs li {
		border: 1px solid #ccc;
		cursor: pointer;
		margin: 0;
		padding: 0;
		    background: #F1F1F1;
				border: 1px solid #DFDFDF;
				border-bottom-width: 0;
    font-size: 13px;
    line-height: 18px;
    color: #0074a2;
    padding: 10px;
    font-weight: normal;
    margin-right: 1px;
	}

	.qm-tabs li.active {
		background: #4EA0D4;
		color: #fff;
		border-right-color: #4EA0D4;
		border-left-color: #4EA0D4;
	}

	.qm-tabs li.active:hover {
		background: #4EA0D4;
		color: #fff;
	}

	.qm-tabs li:hover {
		background: #D9D9D9;
	}



	.qm-tabs li:first-child{
		border-radius: 3px 0 0 0;
		}

	.qm-tabs li:last-child {
		    border-radius: 0 0 0 3px;
				    border-bottom: #ccc solid 1px;
	}

	/* Field Column */
	.qm-field-form {
		display: table;
		padding-left: 20px;
	}

</style>

<script>

jQuery(document).ready(function( $ ) {

	$('.qm-tabs li').click( function() {
		$('.qm-tabs li').removeClass('active');
		$(this).addClass('active');
	});

});

</script>
