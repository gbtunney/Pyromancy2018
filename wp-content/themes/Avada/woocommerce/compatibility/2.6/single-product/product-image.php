<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $woocommerce, $product;

if ( ! Avada()->settings->get( 'disable_woo_gallery' ) ) {
	include WC()->plugin_path() . '/templates/single-product/product-image.php';
	return;
}
?>
<div class="images">

	<div id="slider" class="fusion-flexslider">
		<ul class="slides">
			<?php
				$attachment_count   = count( $product->get_gallery_attachment_ids() );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '[]';
				}

				if ( has_post_thumbnail() ) {

					$props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
					$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
						'title'	 => $props['title'],
						'alt'    => $props['alt'],
					) );
					// Avada Edit
					echo apply_filters(
						'woocommerce_single_product_image_html',
						sprintf(
							'<li><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="iLightbox' . $gallery . '" data-title="%s" data-caption="%s">%s</a></li>',
							esc_url( $props['url'] ),
							esc_attr( $props['title'] ),
							esc_attr( $props['title'] ),
							esc_attr( $props['caption'] ),
							$image
						),
						$post->ID
					);

				} else {

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><img src="%s" alt="Placeholder" /></li>', wc_placeholder_img_src() ), $post->ID );

				}

				/**
				 * From product-thumbnails.php
				 */
				$attachment_ids = $product->get_gallery_attachment_ids();

				$loop = 0;
				// Avada Edit
				//$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

				foreach ( $attachment_ids as $attachment_id ) {

					// Avada Edit
					/*
					$classes = array( 'zoom' );

					if ( $loop == 0 || $loop % $columns == 0 )
						$classes[] = 'first';

					if ( ( $loop + 1 ) % $columns == 0 )
						$classes[] = 'last';
					*/
					$classes[] = 'image-'.$attachment_id;

					$image_link = wp_get_attachment_url( $attachment_id );

					if ( ! $image_link )
						continue;

					// Avada Edit
					// modified image size to shop_single from thumbnail
					$image	   = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ) );
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $attachment_id ) );
					$image_caption = get_post_field( 'post_excerpt', $attachment_id );


					// Avada Edit
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="iLightbox' . $gallery . '" data-title="%s" data-caption="%s">%s</a></li>', $image_link, $image_title, $image_title, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
					//echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );

					$loop++;
				}
			?>
		</ul>
	</div>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
