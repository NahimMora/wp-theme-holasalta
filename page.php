<?php
/**
 * Institutional and standard page template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container hs-container--narrow">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-static-page' ); ?>>
				<header class="hs-page-header">
					<p class="hs-kicker"><?php esc_html_e( 'HolaSalta', 'holasalta-child' ); ?></p>
					<h1><?php the_title(); ?></h1>
				</header>

				<div class="hs-static-page-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>

