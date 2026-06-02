<?php
/**
 * Automatic editorial homepage.
 *
 * @package HolaSalta_Child
 */

get_header();

$featured_query = hs_get_posts_by_category( 'destacadas', 4 );

if ( ! $featured_query->have_posts() ) {
	$featured_query = hs_get_posts_by_category( '', 4 );
}

$featured_posts = $featured_query->posts;
$breaking_news  = hs_get_posts_by_category( '', 3 );
?>

<div class="hs-home">
	<div class="hs-container">
		<section class="hs-hero" aria-labelledby="hs-featured-title">
			<header class="hs-section-header hs-section-header--hero">
				<h1 id="hs-featured-title"><?php esc_html_e( 'Noticias destacadas', 'holasalta-child' ); ?></h1>
			</header>

			<?php if ( $featured_posts ) : ?>
				<div class="hs-hero-grid">
					<div class="hs-hero-main">
						<?php
						$post = $featured_posts[0]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						setup_postdata( $post );
						hs_render_post_card(
							array(
								'variant'       => 'hero-main',
								'heading_level' => 'h2',
								'image_size'    => 'hs-hero',
								'loading'       => 'eager',
								'show_excerpt'  => true,
							)
						);
						?>
					</div>

					<?php if ( count( $featured_posts ) > 1 ) : ?>
						<div class="hs-hero-side">
							<?php
							foreach ( array_slice( $featured_posts, 1 ) as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								setup_postdata( $post );
								hs_render_post_card(
									array(
										'variant' => 'hero-side',
									)
								);
							endforeach;
							?>
						</div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<p class="hs-empty"><?php esc_html_e( 'Publicá tu primera noticia para comenzar a completar la portada.', 'holasalta-child' ); ?></p>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</section>

		<?php if ( $breaking_news->have_posts() ) : ?>
			<section class="hs-breaking" aria-label="<?php esc_attr_e( 'Último momento', 'holasalta-child' ); ?>">
				<strong><?php esc_html_e( 'Último momento', 'holasalta-child' ); ?></strong>
				<ul>
					<?php
					while ( $breaking_news->have_posts() ) :
						$breaking_news->the_post();
						?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile; ?>
				</ul>
			</section>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>

		<?php hs_render_ad_slot( 'home-top', '728x90' ); ?>

		<?php
		get_template_part(
			'template-parts/section-post-grid',
			null,
			array(
				'title'    => 'Últimas noticias',
				'count'    => 8,
				'link_url' => hs_get_posts_page_url(),
			)
		);

		$sections       = hs_get_editorial_sections();
		$first_sections = array_slice( $sections, 0, 4, true );

		foreach ( $first_sections as $slug => $title ) {
			get_template_part(
				'template-parts/section-post-grid',
				null,
				array(
					'title' => $title,
					'slug'  => $slug,
				)
			);
		}

		hs_render_ad_slot( 'home-middle', '728x90' );

		$last_sections = array_slice( $sections, 4, 4, true );

		foreach ( $last_sections as $slug => $title ) {
			get_template_part(
				'template-parts/section-post-grid',
				null,
				array(
					'title' => $title,
					'slug'  => $slug,
				)
			);
		}

		hs_render_ad_slot( 'home-bottom', '728x90' );
		?>
	</div>
</div>

<?php get_footer(); ?>
