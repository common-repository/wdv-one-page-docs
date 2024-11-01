<?php

class Wdv_One_Page_REST_Posts_Controller extends WP_REST_Controller {

	function __construct(){
		$this->namespace = 'wdv-one-page-docs/v1';
	}

	function register_routes(){

		//Shortcode's routes
		register_rest_route( $this->namespace, "/wdvshortcodes/add-shortcode", [
			[
				'methods'   => 'POST',
				'callback'  => [ $this, 'add_shortcodes' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_shortcode_schema' ],
		] );
		register_rest_route( $this->namespace, "/wdvshortcodes/update-shortcode", [
			[
				'methods'   => 'PUT',
				'callback'  => [ $this, 'update_shortcode' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_shortcode_schema' ],
		] );
		register_rest_route( $this->namespace, "/wdvdocs/delete-shortcode/(?P<id>\d+)", [
			[
				'methods'   => 'DELETE',
				'callback'  => [ $this, 'delete_shortcode' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_shortcode_schema' ],
		] );
		register_rest_route( $this->namespace, "/wdvshortcodes", [
			[
				'methods'   => 'GET',
				'callback'  => [ $this, 'get_shortcodes' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_shortcode_schema' ],
		] );
		register_rest_route( $this->namespace, "/wdvshortcodes/(?P<id>\d+)", [
			[
				'methods'   => 'GET',
				'callback'  => [ $this, 'get_shortcode' ],
				'permission_callback' => [ $this, 'get_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_shortcode_schema' ],
		] );

		//Posts routes
		register_rest_route( $this->namespace, "/posts", [
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_posts' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );
		//Pages routes
		register_rest_route( $this->namespace, "/pages", [
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_pages' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );
		//Get post/page permalink routes
	/*	register_rest_route( $this->namespace, "/permalink/(?P<id>\d+)", [
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_permalink' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );*/

		//Get get children routes
		/*register_rest_route( $this->namespace, "/children/(?P<id>\d+)", [      
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_children' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			],
			'schema' => [ $this, 'get_full_item_schema' ],
		] );*/

		//Wdvdocs routes
		register_rest_route( $this->namespace, "/wdvdocs", [
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );
		register_rest_route( $this->namespace, "/wdvdocs/create-post/", [
			[
				'methods'   => 'POST',
				'callback'  => [ $this, 'create_custom_post' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );

		register_rest_route( $this->namespace, "/wdvdocs/update-post/", [
			[
				'methods'   => 'PUT',
				'callback'  => [ $this, 'update_custom_post' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );

		register_rest_route( $this->namespace, "/wdvdocs/delete-post/(?P<id>\d+)", [
			[
				'methods'   => 'DELETE',
				'callback'  => [ $this, 'delete_custom_post' ],
				'permission_callback' => [ $this, 'add_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );

		register_rest_route( $this->namespace, "/wdvdocs/(?P<id>\d+)", [
			[
				'methods'   => 'GET',
				'callback'  => [ $this, 'get_item' ],
				'permission_callback' => [ $this, 'get_item_permissions_check' ],
			],
			'schema' => [ $this, 'get_item_schema' ],
		] );
	}

	function get_items_permissions_check( $request ){
		//if ( ! current_user_can( 'read' ) )
		if ( current_user_can( 'read' ) )
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), [ 'status' => $this->error_status_code() ] );

		return true;
	}

	function add_items_permissions_check( $request ){
		if ( current_user_can( 'read' ) )
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), [ 'status' => $this->error_status_code() ] );

		return true;
	}

	/**
	 * Gets the latest posts and gives them as a rest response.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return WP_Error|array
	 */
	function get_items( $request ){

		$args = [
            'post_type'      => 'wdvdocs',
            'posts_per_page' => -1,
        ];

        $query  = new WP_Query( $args );
        $wdvdocs   = $query->get_posts();
        $result = [];

		if ( empty( $wdvdocs ) )
			return $data;

        foreach ( $wdvdocs as $wdvdoc ) {
            $data     = $this->prepare_item_for_response( $wdvdoc, $request );
            $result[] = $this->prepare_response_for_collection( $data );
        }

		return $result;
	}


	/**
	 * Get posts
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|array
	 */
	function get_posts( $request ){

		$args = [
            'post_type'      => 'post',
            'posts_per_page' => -1,
        ];

        $query  = new WP_Query( $args );
        $posts   = $query->get_posts();
        $result = [];

		if ( empty( $posts ) )
			return $data;

        foreach ( $posts as $post ) {
            $data     = $this->prepare_item_for_response( $post, $request );
            $result[] = $this->prepare_response_for_collection( $data );
        }

		return $result;
		wp_reset_postdata();
	}


	/**
	 * Get pages
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|array
	 */
		function get_pages( $request ){

		$args = [
            'post_type'      => 'page',
            'posts_per_page' => -1,
        ];

        $query  = new WP_Query( $args );
        $posts   = $query->get_posts();
        $result = [];

		if ( empty( $posts ) )
			return $data;

        foreach ( $posts as $post ) {
            $data     = $this->prepare_item_for_response( $post, $request );
            $result[] = $this->prepare_response_for_collection( $data );
        }

		return $result;
		wp_reset_postdata();
	}

	/**
	 * Get permalink
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|array
	 */
		function get_permalink( $request ){
	$id = (int) $request['id'];
	$link = get_permalink( $id );

		if( !$link )
			return array();

		//return $this->prepare_item_for_response( $post, $request );
		return $link;
	}


	/**
	 * Get children
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|array
	 */
/*		function get_children( $request ){
		$args = [
            'post_type'      => 'wdvdocs',
            'posts_per_page' => -1,
        ];

        $query  = new WP_Query( $args );
        $wdvdocs   = $query->get_posts();

		$id = (int) $request['id'];
		$childrens = get_page_children( $id, $wdvdocs );

        $result = [];

		if ( empty( $childrens ) )
			return $data;

        foreach ( $childrens as $children ) {
            $data     = $this->prepare_full_item_for_response( $children, $request );
            $result[] = $this->prepare_response_for_collection( $data );
        }

		return $result;			
	}*/


	/**
	 * Gets a separate resource.
	 *
	 * @param WP_REST_Request $request 
	 *
	 * @return array
	 */
	function get_item( $request ){
		$id = (int) $request['id'];
		$post = get_post( $id );

		if( !$post )
			return array();

		return $this->prepare_item_for_response( $post, $request );
	}


    /**
     * Create a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function create_custom_post($request)
    {
        $parameters = $request->get_params();
        $title = $parameters['title'];
        $content = $parameters['content'];
        $status = $parameters['status'];
        $meta = $parameters['meta'];
        $settings_data = $meta['settings_data'];

        $wdvdoc_post = array(
            'post_title' => $title,
            'post_type' => 'wdvdocs',
            'post_status' => $status,
            'post_parent' => 0,
            'post_author' => get_current_user_id(),
        );

        $wdvdoc_post_id = wp_insert_post($wdvdoc_post);

        $redirect_url = admin_url('/post.php?action=edit&post=' . $wdvdoc_post_id);

        return $redirect_url;      
    }

    /**
     * Create a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function update_custom_post($request)
    {
        $parameters = $request->get_params();
        $title = $parameters['title'];
        $content = $parameters['content'];
        $status = $parameters['status'];
        $parent = $parameters['parent'];
        $meta = $parameters['meta'];
        $settings_data = $meta['settings_data'];

        $wdvdoc_post = array(
            'post_title' => $title,
            'post_type' => 'wdvdocs',
            'post_status' => $status,
            'post_parent' => $parent,
            'post_author' => get_current_user_id(),
        );

        $wdvdoc_post_id = wp_insert_post($wdvdoc_post);

        $redirect_url = admin_url('/post.php?action=edit&post=' . $wdvdoc_post_id);

        return $redirect_url;    
    }

    /**
     * Create a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function add_shortcodes($request)
    {
    	global $wpdb;
        $parameters = $request->get_params();
        $title = $parameters['title'];
        $shortcode = $parameters['shortcode'];
        $docid = $parameters['docid'];
        //$posturl = $parameters['posturl'];
        //$pageurl = $parameters['pageurl'];
        $posttitle = $parameters['posttitlel'];
        $pagetitle = $parameters['pagetitle'];
        $postid = $parameters['postid'];
        $pageid = $parameters['pageid'];
        $hide = $parameters['hide'];

     $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

    $query = "INSERT INTO `$table_name` (`id`, `title`, `shortcode`,`docid`, `posttitle` , `pagetitle`, `postid` , `pageid`, `hide`) VALUES ( null, '{$title}', '{$shortcode}','{$docid}', '{$posttitle}', '{$pagetitle}', '{$postid}', '{$pageid}', '{$hide}')";

    $wdvshortcode = $wpdb->get_results($query);
    return $wdvshortcode;
    }

    /**
     * Create a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function update_shortcode($request)
    {
    	global $wpdb;
        $parameters = $request->get_params();
		$id = $parameters['id'];
        $title = $parameters['title'];
        $shortcode = $parameters['shortcode'];
        $docid = $parameters['docid'];
        //$posturl = $parameters['posturl'];
        //$pageurl = $parameters['pageurl'];
        $posttitle = $parameters['posttitle'];
        $pagetitle = $parameters['pagetitle'];
        $postid = $parameters['postid'];
        $pageid = $parameters['pageid'];
        $hide = $parameters['hide'];

     $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

	$query = "UPDATE `$table_name` SET title='$title', shortcode='$shortcode', docid='$docid', posttitle='$posttitle', pagetitle='$pagetitle', postid='$postid', pageid='$pageid', hide='$hide' WHERE id=$id";

    $wdvshortcode = $wpdb->get_results($query);
    return $wdvshortcode;
    }


   /**
     * Create a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function get_shortcodes($request)
    {
    	global $wpdb;
        $parameters = $request->get_params();

    $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

    $query = "SELECT * FROM `$table_name` WHERE 1";

    $wdvshortcode = $wpdb->get_results($query);
    return $wdvshortcode;
    }

/**
	 * Gets a separate resource.
	 *
	 * @param WP_REST_Request $request 
	 *
	 * @return array
	 */
	function get_shortcode( $request ){
    	global $wpdb;
        $parameters = $request->get_params();
       $id = $parameters['id'];

    $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

    $query = "SELECT * FROM `$table_name` WHERE id=$id";

    $wdvshortcode = $wpdb->get_results($query);
    return $wdvshortcode;
	}

    /**
     * Delete a shortcode through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function delete_shortcode( $request){
		global $wpdb;
        $parameters = $request->get_params();
		$id = (int) $request['id'];

    $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

    $query = "DELETE * FROM `$table_name` WHERE id=$id";

    $wdvshortcode = $wpdb->get_results($query);
    return $wdvshortcode;
    }


    /**
     * Delete a custom post through rest API
     *
     * @since 1.0.0
     * @param mixed $request
     * @return string
     */
    public function delete_custom_post( $request){

	$id = (int) $request['id'];
	$deleted = wp_delete_post($id);
    }

	/**
	 * Gets the custom post type single resource.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return array
	 */
    protected function get_wdvdoc( $id ) {
       $error = new WP_Error( 'rest_doc_invalid_id', __( 'Invalid doc ID.' ), array( 'status' => 404 ) );

        if ( (int) $id <= 0 ) {
            return $error;
        }

        $post = get_post( (int) $id );
        if ( empty( $post ) || empty( $post->ID ) || $this->post_type !== 'wdvdocs' ) {
            return $error;
        }

        return $post;
    }

	// permissions check
	function get_item_permissions_check( $request ){
		return $this->get_items_permissions_check( $request );
	}

	// permissions check
	function add_item_permissions_check( $request ){
		return $this->add_items_permissions_check( $request );
	}
	
	/**
	 * Collects resource data in accordance with the resource scheme.
	 *
	 * @param WP_Post  $post  The resource object from which the original data will be taken.
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return array
	 */
	function prepare_item_for_response( $post, $request ){

		$post_data = [];

		$schema = $this->get_item_schema();

		// We are also renaming the fields to more understandable names.
		if ( isset( $schema['properties']['id'] ) )
			$post_data['id'] = (int) $post->ID;

		if ( isset( $schema['properties']['title'] ) )
			$post_data['title'] = apply_filters( 'the_title', $post->post_title, $post );

		if ( isset( $schema['properties']['parent'] ) )
			$post_data['parent'] = (int) $post->post_parent;

		return $post_data;
	}


	/**
	 * Prepares an individual resource response for adding it to the resource collection.
	 *
	 * @param WP_REST_Response $response Response object.
	 *                                   
	 * @return array|mixed Response data, ready for insertion into collection data.
	 */
	function prepare_response_for_collection( $response ){

		if ( ! ( $response instanceof WP_REST_Response ) ){
			return $response;
		}

		$data = (array) $response->get_data();
		$server = rest_get_server();

		if ( method_exists( $server, 'get_compact_response_links' ) ){
			$links = call_user_func( [ $server, 'get_compact_response_links' ], $response );
		}
		else {
			$links = call_user_func( [ $server, 'get_response_links' ], $response );
		}

		if ( ! empty( $links ) ){
			$data['_links'] = $links;
		}

		return $data;
	}

	## schema.
	function get_item_schema(){
		$schema = [
			// shows which version of the circuit we are using - this is draft 4
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// defines the resource that the circuit describes
			'title'      => 'vehicle',
			'type'       => 'object',
			// in the JSON scheme, you need to specify the properties in the attribute 'properties'.
			'properties' => [
				'id' => [
					'description' => 'Unique identifier for the object.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => true,
				],
				'parent' => [
					'description' => 'parent for the object.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],

				'title' => [
			                'rendered' =>  [
								'description' => 'Title for the object.',
								'type'        => 'string',
								'context'     => [ 'view', 'edit', 'embed' ],
								'readonly'    => true,
							],
            	],

			],
		];

		return $schema;
	}


	## schema.
	function get_shortcode_schema(){
		$schema = [
			// shows which version of the circuit we are using - this is draft 4
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// defines the resource that the circuit describes
			'title'      => 'vehicle',
			'type'       => 'object',
			// in the JSON scheme, you need to specify the properties in the attribute 'properties'.
			'properties' => [
				'id' => [
					'description' => 'Unique identifier for the object.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => true,
				],
				'title' => [
			        'rendered' =>  [
					'description' => 'Title for the object.',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
					],
            	],
				'shortcode' => [
					'description' => 'Shortcode',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],

				'docid' => [
					'description' => 'Unique identifier for the documents.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],

				/*'posturl' => [
					'description' => 'Link for the post.',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],
				'pageurl' => [
					'description' => 'Link for the page.',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],*/
				'posttitle' => [
					'description' => 'Post title.',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],

				'pagetitle' => [
					'description' => 'Page title.',
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],	
				'postid' => [
					'description' => 'Unique identifier for the selected post.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],
				'pageid' => [
					'description' => 'Unique identifier for the selected page.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],	
				'hide' => [
					'description' => 'Hide document.',
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => false,
				],			

			],
		];

		return $schema;
	}


	## Sets the HTTP status code for authorization.
	function error_status_code(){
		return is_user_logged_in() ? 403 : 401;
	}

}
