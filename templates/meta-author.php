<?php
/**
 * Footer template part.
 *
 * @package AMP 1.0.1
 */
 ?>
 <?php $post_author = $this->get( 'post_author' ); ?>
<?php if ( $post_author ) : ?>
	<div class="amp-wp-meta amp-wp-byline">
		<span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span>
	</div>
<?php endif; ?>