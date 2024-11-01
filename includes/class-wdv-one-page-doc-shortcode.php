<?php

/**
 * Shortcode
 */
class Wdv_One_Page_Doc_Shortcode {
public string $shortcodedoc;
    public function __construct() {
        add_shortcode( 'wdvdoc', array( $this, 'shortcode' ) );
    }

    /**
     * Shortcode handler
     *
     * @param  array  $atts
     * @param  string  $content
     *
     * @return string
     */
    //public function shortcode( $atts, $content = '' ) {
    public function shortcode($atts) {

    global $wdv_atts;  
	$shortcodedoc = 'wdvdoc';
    $wdv_atts   = shortcode_atts( array('id'    => '', ), $atts, $shortcodedoc );
   
    $template = new SC101_Template_Loader();

    ob_start();

    $template->get_template_part( 'wdv-one-page-docs-public-wdv-doc-shortcode-display' );

    return ob_get_clean();

    }

}