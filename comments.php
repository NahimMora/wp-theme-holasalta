<?php
/**
 * Comments template.
 *
 * @package HolaSalta_Child
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="hs-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="hs-comments-title">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %s: comment count. */
					_n( '%s comentario', '%s comentarios', get_comments_number(), 'holasalta-child' ),
					number_format_i18n( get_comments_number() )
				)
			);
			?>
		</h2>

		<ol class="hs-comment-list">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 48,
					'style'       => 'ol',
					'short_ping'  => true,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => __( 'Comentarios anteriores', 'holasalta-child' ),
				'next_text' => __( 'Comentarios siguientes', 'holasalta-child' ),
			)
		);
		?>
	<?php endif; ?>

	<?php
	if ( comments_open() ) {
		comment_form(
			array(
				'title_reply'  => __( 'Dejá tu comentario', 'holasalta-child' ),
				'label_submit' => __( 'Publicar comentario', 'holasalta-child' ),
			)
		);
	}
	?>
</section>

