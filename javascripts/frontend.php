<?php
	header("Content-type: text/javascript");
	if (get_post_meta(esc_attr__($_GET['convo-id']), 'prompt', true) != '') :
?>
jQuery(document).ready(function($) {
	$("textarea[name='comment']").parents()
		.map(function () {
			if (this.tagName == "FORM")
				$(this).before('<div id="pj-convo-comment-prompt"><?php esc_attr_e(get_post_meta(esc_attr__($_GET['convo-id']), 'prompt', true)); ?></div>');
		})
});
<?php
	endif;
?>