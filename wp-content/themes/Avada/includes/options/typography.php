<?php
/**
 * Avada Options.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      4.0.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Menu
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_options_section_typography( $sections ) {

	$sections['typography'] = array(
		'label'    => esc_html__( 'Typography', 'Avada' ),
		'id'       => 'heading_typography',
		'is_panel' => true,
		'priority' => 12,
		'icon'     => 'el-icon-fontsize',
		'fields'   => array(
			'body_typography' => array(
				'label'       => esc_html__( 'Body Typography', 'Avada' ),
				'id'          => 'body_typography',
				'type'        => 'sub-section',
				'fields'      => array(
					'body_typography_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> This tab contains general typography options. Additional typography options for specific areas can be found within other tabs. Example: For menu typography options go to the menu tab.', 'Avada' ) . '</div>',
						'id'          => 'body_typography_important_note_info',
						'type'        => 'custom',
					),
					'body_typography' => array(
						'id'          => 'body_typography',
						'label'       => esc_html__( 'Body Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all body text.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
						),
						'default'     => array(
							'font-family'    => 'PT Sans',
							'font-size'      => '13px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#747474',
						),
					),
					'link_color' => array(
						'label'       => esc_html__( 'Link Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of all text links.', 'Avada' ),
						'id'          => 'link_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
				),
			),
			'headers_typography_section' => array(
				'label'       => esc_html__( 'Headers Typography', 'Avada' ),
				'id'          => 'headers_typography_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'headers_typography_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> This tab contains general typography options. Additional typography options for specific areas can be found within other tabs. Example: For menu typography options go to the menu tab.', 'Avada' ) . '</div>',
						'id'          => 'headers_typography_important_note_info',
						'type'        => 'custom',
					),
					'h1_typography' => array(
						'id'          => 'h1_typography',
						'label'       => esc_html__( 'H1 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H1 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '34px',
							'font-weight'    => '400',
							'line-height'    => '1.4',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '0.67em',
							'margin-bottom'  => '0.67em',
						),
					),
					'h2_typography' => array(
						'id'          => 'h2_typography',
						'label'       => esc_html__( 'H2 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H2 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '18px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '0em',
							'margin-bottom'  => '1.1em',
						),
					),
					'h3_typography' => array(
						'id'          => 'h3_typography',
						'label'       => esc_html__( 'H3 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H3 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '16px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '1em',
							'margin-bottom'  => '1em',
						),
					),
					'h4_typography' => array(
						'id'          => 'h4_typography',
						'label'       => esc_html__( 'H4 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H4 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '13px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '1.33em',
							'margin-bottom'  => '1.33em',
						),
					),
					'h5_typography' => array(
						'id'          => 'h5_typography',
						'label'       => esc_html__( 'H5 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H5 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '12px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '1.67em',
							'margin-bottom'  => '1.67em',
						),
					),
					'h6_typography' => array(
						'id'          => 'h6_typography',
						'label'       => esc_html__( 'H6 Headers Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all H6 Headers.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
							'margin-top'     => true,
							'margin-bottom'  => true,
						),
						'default'     => array(
							'font-family'    => 'Antic Slab',
							'font-size'      => '11px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#333333',
							'margin-top'     => '2.33em',
							'margin-bottom'  => '2.33em',
						),
					),
					'post_titles_font_size' => array(
						'label'       => esc_html__( 'Post Titles Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of post titles including archive and single posts. This is a H2 heading.', 'Avada' ),
						'id'          => 'post_titles_font_size',
						'default'     => '18px',
						'type'        => 'dimension',
					),
					'post_titles_font_lh' => array(
						'label'       => esc_html__( 'Post Titles Line Height', 'Avada' ),
						'description' => esc_html__( 'Controls the line height of post titles including archive and single posts. This is a H2 heading.', 'Avada' ),
						'id'          => 'post_titles_font_lh',
						'default'     => '27px',
						'type'        => 'dimension',
					),
					'post_titles_extras_font_size' => array(
						'label'       => esc_html__( 'Post Titles Extras Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of single post titles for "Comments", "Related Posts or Projects" and "Author Titles". This is a H3 heading.', 'Avada' ),
						'id'          => 'post_titles_extras_font_size',
						'default'     => '18px',
						'type'        => 'dimension',
					),
				),
			),
			'custom_webfont_typography_section' => array(
				'label'       => esc_html__( 'Custom Fonts', 'Avada' ),
				'id'          => 'custom_webfont_typography_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'custom_fonts_info' => array(
						'label'       => '',
						'description' => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> Please upload your custom fields below. Once you upload a custom font, <strong>you will have to save your options and reload this page on your browser</strong>. After you reload the page you will be able to select your new fonts - they will be available at the top of the fonts-list in the typography controls.', 'Avada' ) . '</div>',
						'id'          => 'custom_fonts_info',
						'type'        => 'custom',
					),
					'custom_fonts' => array(
						'label'       => esc_html__( 'Custom Fonts', 'Avada' ),
						'description' => esc_html__( 'Upload a custom font to use throughout the site. All files are not necessary but are recommended for full browser support. You can upload as many custom fonts as you need. Click the "Add" button for additional upload boxes.', 'Avada' ),
						'id'          => 'custom_fonts',
						'default'     => array(),
						'type'        => 'repeater',
						'bind_title'  => 'name',
						'limit'       => 50,
						'fields'      => array(
							'name' => array(
								'label'       => esc_html__( 'Font Name (this will be used in the font-family dropdown)', 'Avada' ),
								'description' => '',
								'id'          => 'name',
								'default'     => '',
								'type'        => 'text',
								'class'		  => 'avada-custom-font-name',
							),
							'woff' => array(
								'label'       => 'WOFF',
								'description' => esc_html__( 'Upload the .woff font file.', 'Avada' ),
								'id'          => 'woff',
								'default'     => '',
								'type'        => 'upload',
								'mode'        => false,
							),
							'woff2' => array(
								'label'       => 'WOFF2',
								'description' => esc_html__( 'Upload the .woff2 font file.', 'Avada' ),
								'id'          => 'woff2',
								'default'     => '',
								'type'        => 'upload',
								'mode'        => false,
							),
							'ttf' => array(
								'label'       => 'TTF',
								'description' => esc_html__( 'Upload the .ttf font file.', 'Avada' ),
								'id'          => 'ttf',
								'default'     => '',
								'type'        => 'upload',
								'mode'        => false,
							),
							'svg' => array(
								'label'       => 'SVG',
								'description' => esc_html__( 'Upload the .svg font file.', 'Avada' ),
								'id'          => 'svg',
								'default'     => '',
								'type'        => 'upload',
								'mode'        => false,
							),
							'eot' => array(
								'label'       => 'EOT',
								'description' => esc_html__( 'Upload the .eot font file.', 'Avada' ),
								'id'          => 'eot',
								'default'     => '',
								'type'        => 'upload',
								'mode'        => false,
							),
						),
					),
				),
			),
		),
	);

	return $sections;

}
