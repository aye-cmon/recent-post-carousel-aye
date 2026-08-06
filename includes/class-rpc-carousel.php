<?php
defined('ABSPATH') || exit;
class WPBakeryShortCode_Rpc_Carousel extends WPBakeryShortCodesContainer {

	public function getSortedGroups( $content ) {
		$groups = array();
		preg_match_all( '/\[rpc_group([^\]]*)\](.*?)\[\/rpc_group\]/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) { return $groups; }

		foreach ( $matches as $index => $match ) {
			$atts = shortcode_parse_atts( $match[1] );
			$atts = is_array( $atts ) ? $atts : array();
			$date_raw  = isset( $atts['group_date'] ) ? trim( $atts['group_date'] ) : '';
			$timestamp = $date_raw ? strtotime( $date_raw ) : false;

			$groups[] = array(
				'atts'  => $atts,
				'inner'  => $match[2],
				'timestamp'  => $timestamp !== false ? $timestamp : 0,
				'priority'  => isset( $atts['sort_priority'] ) && $atts['sort_priority'] !== '' ? floatval( $atts['sort_priority'] ) : 0,
				'original_index' => $index,
			);
		}

		usort( $groups, function ( $a, $b ) {
			if ( $a['timestamp'] !== $b['timestamp'] ) { return $b['timestamp'] <=> $a['timestamp']; }
			if ( $a['priority']  !== $b['priority']  ) { return $b['priority']  <=> $a['priority'];  }
			return $a['original_index'] <=> $b['original_index'];
		} );

		return $groups;
	}

	public function getChildDefaultContent() {
		return '[rpc_group group_date="' . date( 'Y-m-d' ) . '"][/rpc_group]';
	}

	public function content( $atts, $content = null ) {
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		extract( $atts );

		$groups_per_page = max( 1, intval( $groups_per_page ) );
		$show_dots = ( 'yes' === $show_dots );
		$autoplay  = ( 'yes' === $autoplay );
		$autoplay_delay  = $autoplay ? max( 1000, intval( $autoplay_delay ) ) : false;

		$groups = $this->getSortedGroups( $content );
		if ( empty( $groups ) ) {
			return '<div class="rpc-carousel-empty">' . esc_html__( 'No groups added yet.', 'rpc' ) . '</div>';
		}

		$pages = array_chunk( $groups, $groups_per_page );
		$num_pages = count( $pages );
		$uid = 'rpc-' . substr( md5( serialize( $atts ) . microtime() ), 0, 8 );

		$wrapper_classes = array( 'rpc-carousel-wrap' );
		if ( ! empty( $el_class ) ) { $wrapper_classes[] = esc_attr( $el_class ); }

		$data_interval = $autoplay_delay ? esc_attr( $autoplay_delay ) : 'false';

		// Outer wrapper (position: relative anchor for nothing — dots live outside carousel)
		$output  = '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';

		//Bootstrap carousel (NO indicators inside)
		$output .= '<div id="' . esc_attr( $uid ) . '" class="carousel slide rpc-carousel"';
		$output .= ' data-ride="carousel" data-interval="' . $data_interval . '" data-wrap="true">';

		$output .= '<div class="carousel-inner">';
		foreach ( $pages as $page_index => $page_groups ) {
			$slide_class = ( 0 === $page_index ) ? 'carousel-item active rpc-page' : 'carousel-item rpc-page';
			$output .= '<div class="' . esc_attr( $slide_class ) . '">';
			$output .= '<div class="rpc-page-inner">';

			foreach ( $page_groups as $group ) {
				$group_atts  = $group['atts'];
				$group_class = array( 'rpc-group' );
				if ( ! empty( $group_atts['el_class'] ) ) { $group_class[] = esc_attr( $group_atts['el_class'] ); }
				$output .= '<div class="' . esc_attr( implode( ' ', $group_class ) ) . '">';
				$output .= do_shortcode( $group['inner'] );
				$output .= '</div>';
			}

			$output .= '</div>'; // .rpc-page-inner
			$output .= '</div>'; // .carousel-item
		}
		$output .= '</div>'; // .carousel-inner
		$output .= '</div>'; // #uid .carousel

		if ( $show_dots && $num_pages > 1 ) {
			$output .= '<ol class="rpc-indicators">';
			for ( $i = 0; $i < $num_pages; $i++ ) {
				$active  = ( 0 === $i ) ? ' class="active"' : '';
				$output .= '<li data-target="#' . esc_attr( $uid ) . '"';
				$output .= ' data-slide-to="' . esc_attr( $i ) . '"' . $active . '></li>';
			}
			$output .= '</ol>';
		}

		$output .= '</div>'; // .rpc-carousel-wrap

		return $output;
	}
}
