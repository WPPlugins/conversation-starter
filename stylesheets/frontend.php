<?php
	header("Content-type: text/css");
	global $convo_starter;
	$padding			=$convo_starter->padding();
	$margin				=$convo_starter->margin();
	$show_border		=$convo_starter->showBorder();
	$border_color		=$convo_starter->borderColor();
	$border_type		=$convo_starter->borderType();
	$border_width		=$convo_starter->borderWidth();
	$background_color	=$convo_starter->backgroundColor();
	$font_color			=$convo_starter->fontColor();
	$font_weight		=$convo_starter->fontWeight();
?> 
#pj-convo-comment-prompt {
	padding: <?php echo $padding['top'].'px '.$padding['right'].'px '.$padding['bottom'].'px '.$padding['left'].'px'; ?>;
	margin: <?php echo $margin['top'].'px '.$margin['right'].'px '.$margin['bottom'].'px '.$margin['left'].'px'; ?>;
	<?php if ($show_border) : ?>
	border: <?php echo $border_width.'px '.$border_type.' '.$border_color; ?>;
	<?php endif; ?>background-color: <?php echo $background_color; ?>;
	color: <?php echo $font_color; ?>;
	font-weight: <?php echo $font_weight; ?>;
}