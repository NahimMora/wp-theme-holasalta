<?php
/**
 * Posts page: "Últimas noticias".
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container">
		<header class="hs-page-header">
			<p class="hs-kicker"><?php esc_html_e( 'HolaSalta', 'holasalta-child' ); ?></p>
			<h1><?php esc_html_e( 'Últimas noticias', 'holasalta-child' ); ?></h1>
			<p><?php esc_html_e( 'La información más reciente de Salta y el país.', 'holasalta-child' ); ?></p>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<?php if ( have_posts() ) : ?>
			<div class="hs-grid hs-grid-archive">
				<?php
				while ( have_posts() ) :
					the_post();
					hs_render_post_card(
						array(
							'show_excerpt' => true,
						)
					);
				endwhile;
				?>
			</div>

			<?php hs_render_pagination(); ?>
		<?php else : ?>
			<p class="hs-empty"><?php esc_html_e( 'Todavía no hay noticias publicadas.', 'holasalta-child' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>

