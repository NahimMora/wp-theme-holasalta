<?php
/**
 * Related news section.
 *
 * @package HolaSalta_Child
 */

$related = hs_get_related_posts( get_the_ID(), 4 );
?>

<?php if ( $related->have_posts() ) : ?>
	<section class="hs-related-posts" aria-labelledby="hs-related-title">
		<header class="hs-section-header">
			<h2 id="hs-related-title"><?php esc_html_e( 'Noticias relacionadas', 'holasalta-child' ); ?></h2>
		</header>
		<div class="hs-grid hs-grid-related">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				hs_render_post_card();
			endwhile;
			?>
		</div>
	</section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

