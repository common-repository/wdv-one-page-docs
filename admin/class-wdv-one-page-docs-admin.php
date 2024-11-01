<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/admin
 * @author     Wdvillage <wdvillage100@gmail.com>
 */
class Wdv_One_Page_Docs_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdv_One_Page_Docs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdv_One_Page_Docs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wdv-one-page-docs-admin.css', array(), $this->version, 'all' );

		if (isset($_GET['page']) && ($_GET['page'] == 'wdv-one-page-docs-dashboard' )) {
		wp_enqueue_style( $this->plugin_name .'-admin-styles',  plugin_dir_url( __FILE__ ) . '../includes/wdv-table/dist/wdv-table/styles.d15603a5505486538cae.css', array(), $this->version, 'all' );
		}             

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdv_One_Page_Docs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdv_One_Page_Docs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wdv-one-page-docs-admin.js', array( 'jquery' ), $this->version, false );

		if (isset($_GET['page']) && ($_GET['page'] == 'wdv-one-page-docs-dashboard' )) {
                
		wp_enqueue_script( $this->plugin_name .'-admin-runtime-es2015', plugin_dir_url( __FILE__ ) . '../includes/wdv-table/dist/wdv-table/runtime-es2015.0dae8cbc97194c7caed4.js', array(), $this->version, true );


        wp_enqueue_script( $this->plugin_name .'-admin-polyfills-es2015', plugin_dir_url( __FILE__ ) . '../includes/wdv-table/dist/wdv-table/polyfills-es2015.f332a089ad1600448873.js', array(), $this->version, true );
        
                
		wp_enqueue_script( $this->plugin_name .'-admin-main-es2015',  plugin_dir_url( __FILE__ ) . '../includes/wdv-table/dist/wdv-table/main-es2015.39610d7f768870db9733.js', array(), $this->version, true );
		}

	}
	

        //---------------------------------------------------
        // MENU SECTION
        //---------------------------------------------------
        public function menu_section() {
          add_menu_page( 'WDV One Page Docs', __('WDV One Page Docs', 'wdv-one-page-docs'), 'manage_options', 'wdv-one-page-docs-dashboard', array($this, 'menu_section_display'), 'dashicons-thumbs-up', 24 );
        }

        public function menu_section_display(){
            include_once plugin_dir_path( __FILE__ ) . 'partials/wdv-one-page-docs-admin-dashboard-display.php';
        }  


        //---------------------------------------------------
        // REST API
        //---------------------------------------------------      
        public function prefix_register_wdv_one_page_rest_routes() {

		$controller = new Wdv_One_Page_REST_Posts_Controller();
		$controller->register_routes();
		}	

    /**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
        
        /**
	 * Post Type: WDVDocs.
	 */
    public static function wdv_docs_post_type_docs() {

        $labels = array(
            'name'                => _x( 'Docs', 'Post Type General Name', 'wdv-one-page-docs' ),
            'singular_name'       => _x( 'Doc', 'Post Type Singular Name', 'wdv-one-page-docs' ),
            'menu_name'           => __( 'Documentation', 'wdv-one-page-docs' ),
            'parent_item_colon'   => __( 'Parent Doc', 'wdv-one-page-docs' ),
            'all_items'           => __( 'All Documentations', 'wdv-one-page-docs' ),
            'view_item'           => __( 'View Documentation', 'wdv-one-page-docs' ),
            'add_new_item'        => __( 'Add Documentation', 'wdv-one-page-docs' ),
            'add_new'             => __( 'Add New', 'wdv-one-page-docs' ),
            'edit_item'           => __( 'Edit Documentation', 'wdv-one-page-docs' ),
            'update_item'         => __( 'Update Documentation', 'wdv-one-page-docs' ),
            'search_items'        => __( 'Search Documentation', 'wdv-one-page-docs' ),
            'not_found'           => __( 'Not documentation found', 'wdv-one-page-docs' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'wdv-one-page-docs' ),
        );
        $rewrite = array(
            'slug'                => 'wdvdocs',
            'with_front'          => true,
            'pages'               => true,
            'feeds'               => true,
        );
        $args = array(
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'comments' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-portfolio',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );

        register_post_type( "wdvdocs", $args );
    }


}
