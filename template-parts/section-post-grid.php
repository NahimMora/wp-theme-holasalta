<?php
/**
 * Reusable home section with a news grid.
 *
 * @package HolaSalta_Child
 */

$defaults = array(
	'title'        => '',
	'slug'         => '',
	'count'        => 8,
	'link_url'     => '',
	'show_excerpt' => false,
);
$section  = wp_parse_args( $args, $defaults );
$query    = hs_get_posts_by_category( $section['slug'], $section['count'] );
$link_url = $section['link_url'];

if ( ! $link_url && $section['slug'] ) {
	$category = get_category_by_slug( $section['slug'] );
	$link_url = $category ? get_category_link( $category->term_id ) : '';
}
?>

<section class="hs-section">
	<header class="hs-section-header">
		<h2><?php echo esc_html( $section['title'] ); ?></h2>
		<?php if ( $link_url ) : ?>
			<a class="hs-more-link" href="<?php echo esc_url( $link_url ); ?>">
				<?php esc_html_e( 'Ver más', 'holasalta-child' ); ?>
			</a>
		<?php endif; ?>
	</header>

	<?php if ( $query->have_posts() ) : ?>
		<div class="hs-grid hs-grid-4x2">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				hs_render_post_card(
					array(
						'show_excerpt' => $section['show_excerpt'],
					)
				);
			endwhile;
			?>
		</div>
	<?php else : ?>
		<p class="hs-empty"><?php esc_html_e( 'Todavía no hay noticias publicadas en esta sección.', 'holasalta-child' ); ?></p>
	<?php endif; ?>
</section>

<?php wp_reset_postdata(); ?>
