<?php
/**
 * Slide template.
 *
 * @package Fusion-Slider
 * @subpackage Templates
 * @since 1.0.0
 */

?>
<?php $max_width = ( 'fade' === $slider_settings['animation'] ) ? 'max-width:' . $slider_settings['slider_width'] : ''; ?>

<div class="fusion-slider-container fusion-slider-<?php the_ID(); ?> <?php echo esc_attr( $slider_class ); ?>-container" style="height:<?php echo esc_attr( $slider_settings['slider_height'] ); ?>;max-width:<?php echo esc_attr( $slider_settings['slider_width'] ); ?>;">
	<style type="text/css" scoped="scoped">
	.fusion-slider-<?php the_ID(); ?> .flex-direction-nav a {
		<?php
		if ( $slider_settings['nav_box_width'] ) {
			echo 'width:' . esc_attr( $slider_settings['nav_box_width'] ) . ';';
		}
		if ( $slider_settings['nav_box_height'] ) {
			echo 'height:' . esc_attr( $slider_settings['nav_box_height'] ) . ';';
			echo 'line-height:' . esc_attr( $slider_settings['nav_box_height'] ) . ';';
		}
		if ( $slider_settings['nav_arrow_size'] ) {
			echo 'font-size:' . esc_attr( $slider_settings['nav_arrow_size'] ) . ';';
		}
		?>
	}
	</style>
	<div class="fusion-slider-loading"><?php esc_attr_e( 'Loading...', 'fusion-core' ); ?></div>
	<div class="tfs-slider flexslider main-flex<?php echo esc_attr( $slider_class ); ?>" style="max-width:<?php echo esc_attr( $slider_settings['slider_width'] ); ?>;" <?php echo $slider_data; // WPCS: XSS ok. ?>>
		<ul class="slides" style="<?php echo esc_attr( $max_width ) ?>;">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php
				$metadata = get_metadata( 'post', get_the_ID() );
				$background_image = '';
				$background_class = '';

				$img_width = '';
				$image_url = array( '', '' );

				if ( isset( $metadata['pyre_type'][0] ) && 'image' === $metadata['pyre_type'][0] && has_post_thumbnail() ) {
					$image_id         = get_post_thumbnail_id();
					$image_url        = wp_get_attachment_image_src( $image_id, 'full', true );
					$background_image = 'background-image: url(' . $image_url[0] . ');';
					$background_class = 'background-image';
					$img_width        = $image_url[1];
				}

				$aspect_ratio       = '16:9';
				$video_attributes   = '';
				$youtube_attributes = '';
				$vimeo_attributes   = '';
				$data_mute          = 'no';
				$data_loop          = 'no';
				$data_autoplay      = 'no';

				if ( isset( $metadata['pyre_aspect_ratio'][0] ) && $metadata['pyre_aspect_ratio'][0] ) {
					$aspect_ratio = $metadata['pyre_aspect_ratio'][0];
				}

				if ( isset( $metadata['pyre_mute_video'][0] ) && 'yes' === $metadata['pyre_mute_video'][0] ) {
					$video_attributes = 'muted';
					$data_mute        = 'yes';
				}

				// Do not set the &auoplay=1 attributes, as this is done in js to make sure the page is fully loaded before the video begins to play.
				if ( isset( $metadata['pyre_autoplay_video'][0] ) && 'yes' === $metadata['pyre_autoplay_video'][0] ) {
					$video_attributes   .= ' autoplay';
					$data_autoplay       = 'yes';
				}

				if ( isset( $metadata['pyre_loop_video'][0] ) && 'yes' === $metadata['pyre_loop_video'][0] ) {
					$video_attributes   .= ' loop';
					$youtube_attributes .= '&amp;loop=1&amp;playlist=' . $metadata['pyre_youtube_id'][0];
					$vimeo_attributes   .= '&amp;loop=1';
					$data_loop           = 'yes';
				}

				if ( isset( $metadata['pyre_hide_video_controls'][0] ) && 'no' === $metadata['pyre_hide_video_controls'][0] ) {
					$video_attributes   .= ' controls';
					$youtube_attributes .= '&amp;controls=1';
					$video_zindex        = 'z-index: 1;';
				} else {
					$youtube_attributes .= '&amp;controls=0';
					$video_zindex        = 'z-index: -99;';
				}

				$heading_color = 'color:#fff;';

				if ( isset( $metadata['pyre_heading_color'][0] ) && $metadata['pyre_heading_color'][0] ) {
					$heading_color = 'color:' . $metadata['pyre_heading_color'][0] . ';';
				}

				$heading_bg = '';

				if ( isset( $metadata['pyre_heading_bg'][0] ) && 'yes' === $metadata['pyre_heading_bg'][0] ) {
					$heading_bg = 'background-color: rgba(0,0,0, 0.4);';
					if ( isset( $metadata['pyre_heading_bg_color'][0] ) && $metadata['pyre_heading_bg_color'][0] ) {
						$rgb        = fusion_hex2rgb( $metadata['pyre_heading_bg_color'][0] );
						$heading_bg = 'background-color: rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . 0.4 . ');';
					}
				}

				$caption_color = 'color:#fff;';

				if ( isset( $metadata['pyre_caption_color'][0] ) && $metadata['pyre_caption_color'][0] ) {
					$caption_color = 'color:' . $metadata['pyre_caption_color'][0] . ';';
				}

				$caption_bg = '';

				if ( isset( $metadata['pyre_caption_bg'][0] ) && 'yes' === $metadata['pyre_caption_bg'][0] ) {
					$caption_bg = 'background-color: rgba(0, 0, 0, 0.4);';

					if ( isset( $metadata['pyre_caption_bg_color'][0] ) && $metadata['pyre_caption_bg_color'][0] ) {
						$rgb        = fusion_hex2rgb( $metadata['pyre_caption_bg_color'][0] );
						$caption_bg = 'background-color: rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . 0.4 . ');';
					}
				}

				$video_bg_color = '';

				if ( isset( $metadata['pyre_video_bg_color'][0] ) && $metadata['pyre_video_bg_color'][0] ) {
					$video_bg_color_hex = fusion_hex2rgb( $metadata['pyre_video_bg_color'][0] );
					$video_bg_color     = 'background-color: rgba(' . $video_bg_color_hex[0] . ', ' . $video_bg_color_hex[1] . ', ' . $video_bg_color_hex[2] . ', 0.4);';
				}

				$video = false;

				if ( isset( $metadata['pyre_type'][0] ) ) {
					if ( isset( $metadata['pyre_type'][0] ) && in_array( $metadata['pyre_type'][0], array( 'self-hosted-video', 'youtube', 'vimeo' ), true ) ) {
						$video = true;
					}
				}

				if ( isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' === $metadata['pyre_type'][0] ) {
					$background_class = 'self-hosted-video-bg';
				}

				$heading_font_size = 'font-size:60px;line-height:80px;';
				if ( isset( $metadata['pyre_heading_font_size'][0] ) && $metadata['pyre_heading_font_size'][0] ) {
					$line_height       = $metadata['pyre_heading_font_size'][0] * 1.2;
					$heading_font_size = 'font-size:' . $metadata['pyre_heading_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
				}

				$caption_font_size = 'font-size: 24px;line-height:38px;';
				if ( isset( $metadata['pyre_caption_font_size'][0] ) && $metadata['pyre_caption_font_size'][0] ) {
					$line_height       = $metadata['pyre_caption_font_size'][0] * 1.2;
					$caption_font_size = 'font-size:' . $metadata['pyre_caption_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
				}

				$heading_styles = $heading_color . $heading_font_size;
				$caption_styles = $caption_color . $caption_font_size;
				$heading_title_sc_wrapper_class = '';
				$caption_title_sc_wrapper_class = '';

				if ( ! isset( $metadata['pyre_heading_separator'][0] ) ) {
					$metadata['pyre_heading_separator'][0] = 'none';
				}

				if ( ! isset( $metadata['pyre_caption_separator'][0] ) ) {
					$metadata['pyre_caption_separator'][0] = 'none';
				}

				if ( 'center' !== $metadata['pyre_content_alignment'][0] ) {
					$metadata['pyre_heading_separator'][0] = 'none';
					$metadata['pyre_caption_separator'][0] = 'none';
				}

				if ( 'center' === $metadata['pyre_content_alignment'][0] ) {
					if ( 'none' !== $metadata['pyre_heading_separator'][0] ) {
						$heading_title_sc_wrapper_class = ' fusion-block-element';
					}

					if ( 'none' !== $metadata['pyre_caption_separator'][0] ) {
						$caption_title_sc_wrapper_class = ' fusion-block-element';
					}
				}

				$data_display = 'cover';
				if ( isset( $metadata['pyre_video_display'][0] ) && 'contain' === $metadata['pyre_video_display'][0] ) {
					$data_display = 'contain';
				}
				?>
				<li data-mute="<?php echo esc_html( $data_mute ); ?>" data-loop="<?php echo esc_html( $data_loop ); ?>" data-autoplay="<?php echo esc_html( $data_autoplay ); ?>">
					<div class="slide-content-container slide-content-<?php echo ( isset( $metadata['pyre_content_alignment'][0] ) && $metadata['pyre_content_alignment'][0] ) ? esc_attr( $metadata['pyre_content_alignment'][0] ) : ''; ?>" style="display: none;">
						<div class="slide-content" style="<?php echo esc_html( $content_max_width ); ?>">
							<?php if ( isset( $metadata['pyre_heading'][0] ) && $metadata['pyre_heading'][0] ) : ?>
								<div class="heading <?php echo ( $heading_bg ) ? 'with-bg' : ''; ?>">
									<div class="fusion-title-sc-wrapper<?php echo esc_attr( $heading_title_sc_wrapper_class ); ?>" style="<?php echo esc_html( $heading_bg ); ?>">
										<?php echo do_shortcode( '[fusion_title size="2" content_align="' . $metadata['pyre_content_alignment'][0] . '" sep_color="' . $metadata['pyre_heading_color'][0] . '" margin_top="0px" margin_bottom="0px" style_type="' . $metadata['pyre_heading_separator'][0] . '" style_tag="' . $heading_styles . '"]' . do_shortcode( $metadata['pyre_heading'][0] ) . '[/fusion_title]' ); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( isset( $metadata['pyre_caption'][0] ) && $metadata['pyre_caption'][0] ) : ?>
								<div class="caption <?php echo ( $caption_bg ) ? 'with-bg' : ''; ?>">
									<div class="fusion-title-sc-wrapper<?php echo esc_attr( $caption_title_sc_wrapper_class ); ?>" style="<?php echo esc_attr( $caption_bg ); ?>">
										<?php echo do_shortcode( '[fusion_title size="3" content_align="' . $metadata['pyre_content_alignment'][0] . '" sep_color="' . $metadata['pyre_caption_color'][0] . '" margin_top="0px" margin_bottom="0px" style_type="' . $metadata['pyre_caption_separator'][0] . '" style_tag="' . $caption_styles . '"]' . do_shortcode( $metadata['pyre_caption'][0] ) . '[/fusion_title]' ); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'button' === $metadata['pyre_link_type'][0] ) : ?>
								<div class="buttons" >
									<?php if ( isset( $metadata['pyre_button_1'][0] ) && $metadata['pyre_button_1'][0] ) : ?>
										<div class="tfs-button-1"><?php echo do_shortcode( $metadata['pyre_button_1'][0] ); ?></div>
									<?php endif; ?>
									<?php if ( isset( $metadata['pyre_button_2'][0] ) && $metadata['pyre_button_2'][0] ) : ?>
										<div class="tfs-button-2"><?php echo do_shortcode( $metadata['pyre_button_2'][0] ); ?></div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'full' === $metadata['pyre_link_type'][0] && isset( $metadata['pyre_slide_link'][0] ) && $metadata['pyre_slide_link'][0] ) : ?>
						<a href="<?php echo esc_url_raw( $metadata['pyre_slide_link'][0] ); ?>" class="overlay-link" <?php echo ( isset( $metadata['pyre_slide_target'][0] ) && 'yes' === $metadata['pyre_slide_target'][0] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> aria-label="<?php the_title(); ?>"></a>
					<?php endif; ?>
					<?php if ( isset( $metadata['pyre_preview_image'][0] ) && $metadata['pyre_preview_image'][0] && isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' === $metadata['pyre_type'][0] ) : ?>
						<div class="mobile_video_image" style="background-image: url('<?php echo esc_url_raw( Fusion_Sanitize::css_asset_url( $metadata['pyre_preview_image'][0] ) ); ?>');"></div>
					<?php elseif ( isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' === $metadata['pyre_type'][0] ) : ?>
						<div class="mobile_video_image" style="background-image: url('<?php echo esc_url_raw( Fusion_Sanitize::css_asset_url( FUSION_CORE_URL . '/images/video_preview.jpg' ) ); ?>');"></div>
					<?php endif; ?>
					<?php if ( $video_bg_color && true === $video || 1 === $video || '1' === $video || 'true' === $video ) : ?>
						<div class="overlay" style="<?php echo esc_html( $video_bg_color ); ?>"></div>
					<?php endif; ?>
					<div class="background <?php echo esc_attr( $background_class ); ?>" style="<?php echo esc_html( $background_image ); ?>max-width:<?php echo esc_attr( $slider_settings['slider_width'] ); ?>;height:<?php echo esc_attr( $slider_settings['slider_height'] ); ?>;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo esc_url_raw( $image_url[0] ); ?>', sizingMethod='scale');-ms-filter:'progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo esc_url_raw( $image_url[0] ); ?>', sizingMethod='scale')';" data-imgwidth="<?php echo esc_attr( $img_width ); ?>">
						<?php if ( isset( $metadata['pyre_type'][0] ) ) : ?>
							<?php // @codingStandardsIgnoreLine ?>
							<?php if ( 'self-hosted-video' === $metadata['pyre_type'][0] && ( $metadata['pyre_webm'][0] || $metadata['pyre_mp4'][0] || $metadata['pyre_ogg'][0] ) && ( ( function_exists( 'avada_jetpack_is_mobile' ) && ! avada_jetpack_is_mobile() ) || ( function_exists( 'jetpack_is_mobile' ) && ! jetpack_is_mobile() ) || ! wp_is_mobile() ) ) : ?>
								<video width="1800" height="700" <?php echo $video_attributes; // WPCS: XSS ok. ?> preload="auto">
									<?php if ( array_key_exists( 'pyre_ogg', $metadata ) && $metadata['pyre_ogg'][0] ) : ?>
										<source src="<?php echo esc_url_raw( $metadata['pyre_ogg'][0] ); ?>" type="video/ogg">
									<?php endif; ?>
									<?php if ( array_key_exists( 'pyre_webm', $metadata ) && $metadata['pyre_webm'][0] ) : ?>
										<source src="<?php echo esc_url_raw( $metadata['pyre_webm'][0] ); ?>" type="video/webm">
									<?php endif; ?>
									<?php if ( array_key_exists( 'pyre_mp4', $metadata ) && $metadata['pyre_mp4'][0] ) : ?>
										<source src="<?php echo esc_url_raw( $metadata['pyre_mp4'][0] ); ?>" type="video/mp4">
									<?php endif; ?>
								</video>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_youtube_id'][0] ) && 'youtube' === $metadata['pyre_type'][0] && $metadata['pyre_youtube_id'][0] ) : ?>
							<div style="position: absolute; top: 0; left: 0; <?php echo esc_attr( $video_zindex ); ?> width: 100%; height: 100%" data-youtube-video-id="<?php echo esc_attr( $metadata['pyre_youtube_id'][0] ); ?>" data-video-aspect-ratio="<?php echo esc_attr( $aspect_ratio ); ?>" data-display="<?php echo esc_attr( $data_display ); ?>">
								<div id="video-<?php echo esc_attr( $metadata['pyre_youtube_id'][0] ); ?>-inner">
									<iframe height="100%" width="100%" src="https://www.youtube.com/embed/<?php echo esc_attr( $metadata['pyre_youtube_id'][0] ); ?>?wmode=transparent&amp;modestbranding=1&amp;showinfo=0&amp;autohide=1&amp;enablejsapi=1&amp;rel=0&amp;vq=hd720&amp;<?php echo esc_attr( $youtube_attributes ); ?>"></iframe>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_vimeo_id'][0] ) &&  'vimeo' === $metadata['pyre_type'][0] && $metadata['pyre_vimeo_id'][0] ) : ?>
							<div style="position: absolute; top: 0; left: 0; <?php echo esc_attr( $video_zindex ); ?> width: 100%; height: 100%" data-mute="<?php echo esc_attr( $data_mute ); ?>" data-vimeo-video-id="<?php echo esc_attr( $metadata['pyre_vimeo_id'][0] ); ?>" data-video-aspect-ratio="<?php echo esc_attr( $aspect_ratio ); ?>">
								<iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $metadata['pyre_vimeo_id'][0] ); ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&amp;badge=0<?php echo esc_attr( $vimeo_attributes ); ?>" height="100%" width="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>
