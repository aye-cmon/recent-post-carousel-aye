<?php
defined('ABSPATH') || exit; 

class WPBakeryShortCode_Rpc_Group extends WPBakeryShortCodesContainer {
	public function getChildDefaultContent() { return ''; }
	public function content( $atts, $content = null ) {
		return do_shortcode( $content );
	}
}