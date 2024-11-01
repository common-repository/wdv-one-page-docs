<?php

/**
 * Shortcode
 */
class Wdv_One_Page_Docs_Shortcode {
public string $shortcodedocs;
    public function __construct() {
        add_shortcode( 'wdvdocs', array( $this, 'shortcodes' ) );
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
    public function shortcodes($atts) {

    global $wdvdocs_atts;
	$shortcodedocs = 'wdvdocs';
    $wdvdocs_atts   = shortcode_atts( array(
        'columns'   => '2',
        'txtcolor' => '#fff',
        'bgcolor'  => 'blue',
    ), $atts, $shortcodedocs );


        $template = new SC101_Template_Loader();

        ob_start();

        $template->get_template_part( 'wdv-one-page-docs-public-wdv-docs-shortcode-display' );

        return ob_get_clean();

    }
}