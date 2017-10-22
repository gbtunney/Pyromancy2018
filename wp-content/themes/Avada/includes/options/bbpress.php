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
 * Color settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_options_section_bbpress( $sections ) {
	if ( ! Avada::$is_updating && ! class_exists( 'bbPress' ) && ! class_exists( 'BuddyPress' ) ) {
		return $sections;
	}

	$sections['bbpress'] = array(
		'label'    => esc_html__( 'bbPress', 'Avada' ),
		'id'       => 'bpress_section',
		'priority' => 3,
		'icon'     => 'el-icon-person',
		'fields'   => array(
			'bbp_forum_header_bg' => array(
				'label'       => esc_html__( 'bbPress Forum Header Background Color', 'Avada' ),
				'description' => esc_html__( 'Controls the background color for forum header rows.', 'Avada' ),
				'id'          => 'bbp_forum_header_bg',
				'default'     => '#ebeaea',
				'type'        => 'color-alpha',
			),
			'bbp_forum_header_font_color' => array(
				'label'       => esc_html__( 'bbPress Forum Header Font Color', 'Avada' ),
				'description' => esc_html__( 'Controls the font color for the text in the forum header rows.', 'Avada' ),
				'id'          => 'bbp_forum_header_font_color',
				'default'     => '#747474',
				'type'        => 'color',
			),
			'bbp_forum_border_color' => array(
				'label'       => esc_html__( 'bbPress Forum Border Color', 'Avada' ),
				'description' => esc_html__( 'Controls the border color for all forum surrounding borders.', 'Avada' ),
				'id'          => 'bbp_forum_border_color',
				'default'     => '#ebeaea',
				'type'        => 'color-alpha',
			),
		),
	);

	return $sections;

}
