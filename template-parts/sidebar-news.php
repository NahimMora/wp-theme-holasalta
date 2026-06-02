<?php
/**
 * Sidebar used by news articles and archive pages.
 *
 * @package HolaSalta_Child
 */

$recent_posts    = hs_get_posts_by_category( '', 5 );
$section_ids     = array();
$recent_comments = get_comments(
	array(
		'number' => 5,
		'status' => 'approve',
	)
);

foreach ( array_keys( hs_get_editorial_sections() ) as $section_slug ) {
	$section = get_category_by_slug( $section_slug );

	if ( $section ) {
		$section_ids[] = $section->term_id;
	}
}
?>

<aside class="hs-sidebar" aria-label="<?php esc_attr_e( 'Información adicional', 'holasalta-child' ); ?>">
	<?php hs_render_ad_slot( 'single-top', '300x250' ); ?>

	<?php if ( is_active_sidebar( 'hs-news-sidebar' ) ) : ?>
		<div class="hs-sidebar-block">
			<?php dynamic_sidebar( 'hs-news-sidebar' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $recent_posts->have_posts() ) : ?>
		<section class="hs-sidebar-block">
			<h2><?php esc_html_e( 'Entradas recientes', 'holasalta-child' ); ?></h2>
			<ul class="hs-sidebar-list">
				<?php
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</li>
				<?php endwhile; ?>
			</ul>
		</section>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php hs_render_ad_slot( 'single-middle', '300x250' ); ?>

	<section class="hs-sidebar-block">
		<h2><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></h2>
		<ul class="hs-sidebar-list hs-sidebar-list--compact">
			<?php
			wp_list_categories(
				array(
					'title_li' => '',
					'include'  => implode( ',', $section_ids ),
					'orderby'  => 'name',
				)
			);
			?>
		</ul>
	</section>

	<?php if ( $recent_comments ) : ?>
		<section class="hs-sidebar-block">
			<h2><?php esc_html_e( 'Comentarios recientes', 'holasalta-child' ); ?></h2>
			<ul class="hs-sidebar-list hs-sidebar-list--compact">
				<?php foreach ( $recent_comments as $comment ) : ?>
					<li>
						<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
							<?php
							echo esc_html(
								sprintf(
									/* translators: 1: author, 2: post title. */
									__( '%1$s en %2$s', 'holasalta-child' ),
									get_comment_author( $comment ),
									get_the_title( $comment->comment_post_ID )
								)
							);
							?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</aside>
