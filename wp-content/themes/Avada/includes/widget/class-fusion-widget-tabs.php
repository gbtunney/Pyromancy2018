<?php
/**
 * Widget Class.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Widget class.
 */
class Fusion_Widget_Tabs extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops  = array(
			'classname' => 'fusion-tabs-widget pyre_tabs',
			'description' => 'Popular posts, recent post and comments.',
		);
		$control_ops = array(
			'id_base' => 'pyre_tabs-widget',
		);

		parent::__construct( 'pyre_tabs-widget', 'Avada: Tabs', $widget_ops, $control_ops );

	}

	/**
	 * Echoes the widget content.
	 *
	 * @access public
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {

		global $post;

		extract( $args );

		$posts              = isset( $instance['posts'] ) ? $instance['posts'] : 3;
		$comments           = isset( $instance['comments'] ) ? $instance['comments'] : '3';
		$tags_count         = isset( $instance['tags'] ) ? $instance['tags'] : 3;
		$show_popular_posts = isset( $instance['show_popular_posts'] ) ? true : false;
		$show_recent_posts  = isset( $instance['show_recent_posts'] )  ? true : false;
		$show_comments      = isset( $instance['show_comments'] )      ? true : false;

		$count_tabs = (int) $show_popular_posts + (int) $show_recent_posts + (int) $show_comments ;

		if ( isset( $instance['orderby'] ) ) {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'Highest Comments';
		}

		echo $before_widget; // WPCS: XSS ok.
		?>
		<div class="tab-holder tabs-widget tabs-widget-<?php echo esc_attr( $count_tabs ); ?>">

			<div class="tab-hold tabs-wrapper">

				<ul id="tabs" class="tabset tabs">

					<?php if ( $show_popular_posts ) : ?>
						<li><a href="#tab-popular"><?php esc_attr_e( 'Popular', 'Avada' ); ?></a></li>
					<?php endif; ?>

					<?php if ( $show_recent_posts ) : ?>
						<li><a href="#tab-recent"><?php esc_attr_e( 'Recent', 'Avada' ); ?></a></li>
					<?php endif; ?>

					<?php if ( $show_comments ) : ?>
						<li><a href="#tab-comments"><span class="fusion-icon-bubbles"></span><span class="screen-reader-text"><?php esc_attr_e( 'Comments', 'Avada' ); ?></span></a></li>
					<?php endif; ?>

				</ul>

				<div class="tab-box tabs-container">

					<?php if ( $show_popular_posts ) : ?>

						<div id="tab-popular" class="tab tab_content" style="display: none;">
							<?php
							if ( 'Highest Comments' == $orderby ) {
								$order_string = '&orderby=comment_count';
							} else {
								$order_string = '&meta_key=avada_post_views_count&orderby=meta_value_num';
							}

							$popular_posts = fusion_cached_query( 'showposts=' . $posts . $order_string . '&order=DESC&ignore_sticky_posts=1' );
							?>

							<ul class="news-list">
								<?php if ( $popular_posts->have_posts() ) : ?>
									<?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
										<li>
											<?php if ( has_post_thumbnail() ) : ?>
												<div class="image">
													<a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_post_thumbnail( 'recent-works-thumbnail' ); ?></a>
												</div>
											<?php endif; ?>

											<div class="post-holder">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												<div class="fusion-meta">
													<?php the_time( Avada()->settings->get( 'date_format' ) ); ?>
												</div>
											</div>
										</li>
									<?php endwhile; ?>

									<?php wp_reset_postdata(); ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No posts have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>

					<?php endif; ?>

					<?php if ( $show_recent_posts ) : ?>

						<div id="tab-recent" class="tab tab_content" style="display: none;">

							<?php $recent_posts = fusion_cached_query( 'showposts=' . $tags_count . '&ignore_sticky_posts=1' ); ?>

							<ul class="news-list">
								<?php if ( $recent_posts->have_posts() ) : ?>
									<?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
										<li>
											<?php if ( has_post_thumbnail() ) : ?>
												<div class="image">
													<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'recent-works-thumbnail' ); ?></a>
												</div>
											<?php endif; ?>
											<div class="post-holder">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												<div class="fusion-meta">
													<?php the_time( Avada()->settings->get( 'date_format' ) ); ?>
												</div>
											</div>
										</li>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No posts have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $show_comments ) : ?>

						<div id="tab-comments" class="tab tab_content" style="display: none;">
							<ul class="news-list">
								<?php
								global $wpdb;
								$number = $comments;

								$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
								$the_comments = $wpdb->get_results( $recent_comments );
								?>

								<?php if ( $the_comments ) : ?>
									<?php foreach ( $the_comments as $comment ) : ?>
										<li>
											<div class="image">
												<a><?php echo get_avatar( $comment, '52' ); ?></a>
											</div>

											<div class="post-holder">
												<p><?php printf( esc_attr__( '%s says:', 'Avada' ), esc_attr( strip_tags( $comment->comment_author ) ) ); ?></p>
												<div class="fusion-meta">
													<a class="comment-text-side" href="<?php echo esc_url_raw( get_permalink( $comment->ID ) ); ?>#comment-<?php echo esc_attr( $comment->comment_ID ); ?>" title="<?php printf( esc_attr__( '%1$s on %2$s', 'Avada' ), esc_attr( strip_tags( $comment->comment_author ) ), esc_attr( $comment->post_title ) ); ?>"><?php echo wp_trim_words( strip_tags( $comment->com_excerpt ), 12 ); // WPCS: XSS ok. ?></a>
												</div>
											</div>
										</li>
									<?php endforeach; ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No comments have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>

					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		echo $after_widget; // WPCS: XSS ok.

	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * This function should check that `$new_instance` is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @access public
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['posts']              = $new_instance['posts'];
		$instance['comments']           = $new_instance['comments'];
		$instance['tags']               = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_recent_posts']  = $new_instance['show_recent_posts'];
		$instance['show_comments']      = $new_instance['show_comments'];
		$instance['orderby']            = $new_instance['orderby'];

		return $instance;

	}

	/**
	 * Outputs the settings update form.
	 *
	 * @access public
	 * @param array $instance Current settings.
	 */
	function form( $instance ) {

		$defaults = array(
			'posts'              => 3,
			'comments'           => '3',
			'tags'               => 3,
			'show_popular_posts' => 'on',
			'show_recent_posts'  => 'on',
			'show_comments'      => 'on',
			'orderby'            => esc_attr__( 'Highest Comments', 'Avada' ),
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_attr_e( 'Popular Posts Order By:', 'Avada' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( esc_attr__( 'Highest Comments', 'Avada' ) == $instance['orderby'] ) { echo 'selected="selected"'; } ?>><?php esc_attr_e( 'Highest Comments', 'Avada' ); ?></option>
				<option <?php if ( esc_attr__( 'Highest Views', 'Avada' ) == $instance['orderby'] ) { echo 'selected="selected"'; } ?>><?php esc_attr_e( 'Highest Views', 'Avada' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>"><?php esc_attr_e( 'Number of popular posts:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts' ) ); ?>" value="<?php echo esc_attr( $instance['posts'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php esc_attr_e( 'Number of recent posts:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>" value="<?php echo esc_attr( $instance['tags'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"><?php esc_attr_e( 'Number of comments:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments' ) ); ?>" value="<?php echo esc_attr( $instance['comments'] ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_popular_posts'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_popular_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_popular_posts' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_popular_posts' ) ); ?>"><?php esc_attr_e( 'Show popular posts', 'Avada' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_recent_posts'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_recent_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_recent_posts' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_recent_posts' ) ); ?>"><?php esc_attr_e( 'Show recent posts', 'Avada' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_comments'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_comments' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>"><?php esc_attr_e( 'Show comments', 'Avada' ); ?></label>
		</p>
		<?php

	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
