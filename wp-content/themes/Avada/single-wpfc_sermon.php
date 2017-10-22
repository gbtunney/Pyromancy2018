<?php
/**
 * The template used by the Sermon Manager plugin.
 * Used for single sermons.
 *
 * @see https://wordpress.org/plugins/sermon-manager-for-wordpress/
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<?php $full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
<section id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php if ( Avada()->settings->get( 'blog_pn_nav' ) ) : ?>
		<div class="single-navigation clearfix">
			<?php previous_post_link( '%link', esc_html__( 'Previous', 'Avada' ) ); ?>
			<?php next_post_link( '%link', esc_html__( 'Next', 'Avada' ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
			<?php $full_image = ''; ?>
			<?php if ( 'above' == Avada()->settings->get( 'blog_post_title' ) ) : ?>
				<?php echo avada_render_post_title( $post->ID, false, '', '2' ); // WPCS: XSS ok. ?>
			<?php elseif ( 'disabled' == Avada()->settings->get( 'blog_post_title' ) && Avada()->settings->get( 'disable_date_rich_snippet_pages' ) && Avada()->settings->get( 'disable_rich_snippet_title' ) ) : ?>
				<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
			<?php endif; ?>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_single' ) ) : ?>
					<?php $video = get_post_meta( $post->ID, 'pyre_video', true ); ?>
					<?php if ( 0 < avada_number_of_featured_images() || $video ) : ?>
						<div class="fusion-flexslider flexslider fusion-flexslider-loading fusion-post-slideshow post-slideshow">
							<ul class="slides">
								<?php if ( $video ) : ?>
									<li>
										<div class="full-video">
											<?php echo $video; // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
									<?php $attachment_data  = wp_get_attachment_metadata( get_post_thumbnail_id() ); ?>
									<?php $alt_tag          = esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); ?>
									<li>
										<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
											<a href="<?php echo esc_url_raw( $full_image[0] ); ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( get_post_field( 'post_excerpt', get_post_thumbnail_id() ) ); ?>" data-title="<?php echo esc_attr( get_post_field( 'post_title', get_post_thumbnail_id() ) ); ?>" data-caption="<?php echo esc_attr( get_post_field( 'post_excerpt', get_post_thumbnail_id() ) ); ?>">
												<?php /* translators: The link. */ ?>
												<span class="screen-reader-text"><?php printf( esc_attr__( 'Go to "%s"', 'Avada' ), get_the_title( $post ) ); ?></span>
												<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
											</a>
										<?php else : ?>
											<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
										<?php endif; ?>
									</li>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'post' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_image = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $full_image       = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $attachment_data  = wp_get_attachment_metadata( $attachment_new_id ); ?>
										<?php $alt_tag          = esc_attr( get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true ) ); ?>
										<li>
											<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
												<a href="<?php echo esc_url_raw( $full_image[0] ); ?>" rel="prettyPhoto[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( get_post_field( 'post_excerpt', $attachment_new_id ) ); ?>" data-title="<?php echo esc_attr( get_post_field( 'post_title', $attachment_new_id ) ); ?>" data-caption="<?php echo esc_attr( get_post_field( 'post_excerpt', $attachment_new_id ) ); ?>">
													<?php /* translators: The link. */ ?>
													<span class="screen-reader-text"><?php printf( esc_attr__( 'Go to "%s"', 'Avada' ), get_the_title( $post ) ); ?></span>
													<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
												</a>
											<?php else : ?>
												<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
											<?php endif; ?>
										</li>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( 'below' == Avada()->settings->get( 'blog_post_title' ) ) : ?>
				<?php echo avada_render_post_title( $post->ID, false, '', '2' ); // WPCS: XSS ok. ?>
			<?php endif; ?>
			<div class="post-content">
				<?php echo Avada()->sermon_manager->get_sermon_content(); // WPCS: XSS ok. ?>
				<?php fusion_link_pages(); ?>
			</div>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php echo avada_render_post_metadata( 'single' ); // WPCS: XSS ok. ?>
				<?php if ( Avada()->settings->get( 'social_sharing_box' ) ) : ?>

					<?php $sharingbox_social_icon_options = array(
						'sharingbox'        => 'yes',
						'icon_colors'       => Avada()->settings->get( 'sharing_social_links_icon_color' ),
						'box_colors'        => Avada()->settings->get( 'sharing_social_links_box_color' ),
						'icon_boxed'        => Avada()->settings->get( 'sharing_social_links_boxed' ),
						'icon_boxed_radius' => Fusion_Sanitize::size( Avada()->settings->get( 'sharing_social_links_boxed_radius' ) ),
						'tooltip_placement' => Avada()->settings->get( 'sharing_social_links_tooltip_placement' ),
						'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
						'title'             => wp_strip_all_tags( get_the_title( $post->ID ), true ),
						'description'       => wp_strip_all_tags( get_the_title( $post->ID ), true ),
						'link'              => get_permalink( $post->ID ),
						'pinterest_image'   => ( $full_image ) ? $full_image[0] : '',
					); ?>
					<div class="fusion-sharing-box fusion-single-sharing-box share-box">
						<h4><?php esc_html_e( 'Share This Story, Choose Your Platform!', 'Avada' ); ?></h4>
						<?php echo Avada()->social_sharing->render_social_icons( $sharingbox_social_icon_options ); // WPCS: XSS ok. ?>
					</div>

				<?php endif; ?>

				<?php if ( Avada()->settings->get( 'author_info' ) ) : ?>
					<div class="about-author">
						<?php ob_start(); ?>
						<?php the_author_posts_link(); ?>
						<?php /* translators: The link. */ ?>
						<?php $title = sprintf( __( 'About the Author: %s', 'Avada' ), ob_get_clean() ); ?>
						<?php Avada()->template->title_template( $title, '3' ); ?>
						<div class="about-author-container">
							<div class="avatar">
								<?php echo get_avatar( get_the_author_meta( 'email' ), '72' ); ?>
							</div>
							<div class="description">
								<?php the_author_meta( 'description' ); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php avada_render_related_posts(); // Render Related Posts. ?>

				<?php if ( Avada()->settings->get( 'blog_comments' ) ) : ?>
					<?php wp_reset_postdata(); ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</article>
	<?php endif; ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
