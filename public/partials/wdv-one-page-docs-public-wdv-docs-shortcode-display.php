<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_One_Page_Docs
 * @subpackage Wdv_One_Page_Docs/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php   global $wdvdocs_atts;     


        $columns=$wdvdocs_atts['columns'];

        if($columns==='1'){  $width='95%';  } 
        elseif ($columns==='2') {  $width='45%';  }
        elseif ($columns==='3') {  $width='28%';  }
        else {  $width='20%';  }
        

        $args = [
            'post_type'      => 'wdvdocs',
            'posts_per_page' => -1,
            'post_parent' => 0,
            'orderby'        => 'post_title',
            //'orderby'        => 'CAST([title]  AS unsigned)',
            'order'          => 'ASC',
        ];


        $query  = new WP_Query( $args );
        $parentdocs   = $query->get_posts(); 


//var_dump($parentdocs);
 if ( $parentdocs ) { ?>

	<div class="wdvdocs-shortcode-wrap">
    <ul class="wdvdocs-docs-list">

        <?php foreach ( $parentdocs as $parentdoc ) { ?>
        	<li class="wdvdocs-docs-single"  style="width: <?php echo esc_attr( $width ); ?>">
                <h3><?php echo $parentdoc->post_title; ?></h3>
            <div>
<?php
		$args = [
            'post_type'      => 'wdvdocs',
            'posts_per_page' => -1,
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'post_parent'    => $parentdoc->ID,

        ];

        $query  = new WP_Query( $args );
        $wdvdocs   = $query->get_posts();
        $numberofchildren=count($wdvdocs);

		$id = $parentdoc->ID;
		$childrens = get_page_children( $id, $wdvdocs ); 


        $numerictitles=array();

        foreach ( $childrens as $children ){
         $numerictitle=$children->post_title;
         $numerictitle= preg_replace("/[^0-9.]/", '', $numerictitle);
        // from string to number
         $children->numbtitle = floatval($numerictitle);
      
         array_push($numerictitles, floatval($numerictitle));
        }
        //and now sort it
        sort($numerictitles);

        usort($childrens, function($obj1, $obj2) {
            if ($obj1->numbtitle == $obj2->numbtitle) {
                return 0;
            }
            return $obj1->numbtitle < $obj2->numbtitle ? -1 : 1;
        });
        $childrens = array_slice($childrens, 0, 3); 
		?>



	    <ul class="wdvdocs-children">


	        <?php 
            foreach ( $childrens as $children ) { ?>
	        	<li class="section" >
	                <div><?php echo $children->post_title; ?></div>

	             </li>

	        <?php }
             ?>
            <li>...</li>
	    </ul>
            </div>



                <div class="wdvdocs-doc-link">
                	<?php 
                	    global $wpdb;

					    $table_name = $wpdb->prefix . 'wdv_one_page_shortcodes';

					    $query = "SELECT `postid`, `pageid` FROM `$table_name` WHERE docid=$parentdoc->ID";

					    $currentlinks = $wpdb->get_results($query);

					    foreach ( $currentlinks as $currentlink ) {

                	if ($currentlink->postid!=='0'){ ?>
                    <a href="<?php echo get_permalink( $currentlink->postid ); ?>" target="_blank"><?php echo 'See more'; ?></a>
                	<?php }
                	if ($currentlink->pageid!=='0'){ ?>
                     <a href="<?php echo get_permalink( $currentlink->pageid ); ?>" target="_blank"><?php echo 'See more'; ?></a>               		               		
                	<?php }
                }
                	?>
                </div>
            </li>

        <?php } ?>

    </ul>
</div>

<?php }