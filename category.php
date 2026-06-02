<?php
/**
 * Category archive template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container">
		<header class="hs-page-header">
			<p class="hs-kicker"><?php esc_html_e( 'Sección', 'holasalta-child' ); ?></p>
			<h1><?php single_cat_title(); ?></h1>
			<?php if ( category_description() ) : ?>
				<div class="hs-page-description"><?php echo wp_kses_post( category_description() ); ?></div>
			<?php endif; ?>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<div class="hs-archive-layout">
			<div class="hs-archive-content">
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
					<p class="hs-empty"><?php esc_html_e( 'Todavía no hay noticias publicadas en esta sección.', 'holasalta-child' ); ?></p>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/sidebar-news' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>

