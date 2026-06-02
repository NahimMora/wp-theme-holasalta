<?php
/**
 * Single news article template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell hs-page-shell--single">
	<div class="hs-container">
		<?php
		while ( have_posts() ) :
			the_post();

			$category       = hs_get_primary_category();
			$permalink      = get_permalink();
			$share_url      = rawurlencode( $permalink );
			$share_title    = rawurlencode( get_the_title() );
			$whatsapp_url   = 'https://wa.me/?text=' . $share_title . '%20' . $share_url;
			$facebook_url   = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url;
			$x_url          = 'https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title;
			$is_updated     = get_the_modified_time( 'U' ) > get_the_time( 'U' );
			?>

			<div class="hs-single-layout">
				<div class="hs-single-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-article' ); ?>>
						<nav class="hs-breadcrumb" aria-label="<?php esc_attr_e( 'Migas de pan', 'holasalta-child' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'holasalta-child' ); ?></a>
							<span aria-hidden="true">/</span>
							<?php if ( $category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
							<?php endif; ?>
						</nav>

						<header class="hs-article-header">
							<?php if ( $category ) : ?>
								<a class="hs-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							<?php endif; ?>

							<h1><?php the_title(); ?></h1>

							<?php if ( has_excerpt() ) : ?>
								<p class="hs-article-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>

							<div class="hs-article-meta">
								<span>
									<?php esc_html_e( 'Por', 'holasalta-child' ); ?>
									<a href="<?php echo esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ); ?>">
										<?php echo esc_html( get_the_author() ); ?>
									</a>
								</span>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
								<?php if ( $is_updated ) : ?>
									<time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>">
										<?php
										echo esc_html(
											sprintf(
												/* translators: %s: modified date. */
												__( 'Actualizada: %s', 'holasalta-child' ),
												get_the_modified_date()
											)
										);
										?>
									</time>
								<?php endif; ?>
							</div>
						</header>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="hs-article-image">
								<?php
								the_post_thumbnail(
									'hs-hero',
									array(
										'loading'  => 'eager',
										'decoding' => 'async',
									)
								);
								?>
								<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
									<figcaption><?php echo esc_html( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="hs-article-body">
							<?php
							the_content();
							wp_link_pages();
							?>
						</div>

						<?php if ( get_the_tag_list() ) : ?>
							<div class="hs-tags">
								<strong><?php esc_html_e( 'Etiquetas:', 'holasalta-child' ); ?></strong>
								<?php echo wp_kses_post( get_the_tag_list( '', ' ' ) ); ?>
							</div>
						<?php endif; ?>

						<section class="hs-share" aria-label="<?php esc_attr_e( 'Compartir noticia', 'holasalta-child' ); ?>">
							<strong><?php esc_html_e( 'Compartir:', 'holasalta-child' ); ?></strong>
							<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">WhatsApp</a>
							<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">Facebook</a>
							<a href="<?php echo esc_url( $x_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">X</a>
						</section>
					</article>

					<?php get_template_part( 'template-parts/related-posts' ); ?>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>

				<?php get_template_part( 'template-parts/sidebar-news' ); ?>
			</div>
		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>

