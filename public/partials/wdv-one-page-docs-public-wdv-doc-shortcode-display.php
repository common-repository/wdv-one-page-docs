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


<?php    
 global $wdv_atts; 

        $id=(int)$wdv_atts['id'];

        $wdvdoc = get_post( $id );

        //get_children
        $args = [
            'post_type'      => 'wdvdocs',
            'posts_per_page' => -1,
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'post_parent'    => $id,
        ];

        $query  = new WP_Query( $args );
        $wdvdocs   = $query->get_posts();
        $sections = get_page_children( $id, $wdvdocs ); 


        $secnumerictitles=array();

        foreach ( $sections as $section ){
         $secnumerictitle=$section->post_title;
         $secnumerictitle= preg_replace("/[^0-9.]/", '', $secnumerictitle);
         $section->numbtitle = floatval($secnumerictitle);
      
         array_push($secnumerictitles, floatval($secnumerictitle));

        }
        //and now sort it
        sort($secnumerictitles);

        usort($sections, function($obj1, $obj2) {
            if ($obj1->numbtitle == $obj2->numbtitle) {
                return 0;
            }
            return $obj1->numbtitle < $obj2->numbtitle ? -1 : 1;
        });


        ?>



<script>
function openNav() {
  document.getElementById("mySidepanel").style.width = "350px";
}

function closeNav() {
  document.getElementById("mySidepanel").style.width = "0";
}
</script>




<?php if ( $wdvdoc ) { ?>

<div class="wdvdoc-shortcode-wrap">

<div id="mySidepanel" class="sidepanel">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>

      <ul class="sidepanel-list">
        <h3> <a href="#top" onclick="closeNav()"><?php echo $wdvdoc->post_title; ?></a></h3>

        <?php foreach ( $sections as $section ) { ?>
        <li>
          <?php $link="#".$section->post_title; ?>
          <a href="<?php echo $link; ?>" onclick="closeNav()">
          <?php echo $section->post_title; ?>
          </a>


        <?php
                $argss = [
                    'post_type'      => 'wdvdocs',
                    'posts_per_page' => -1,
                    'orderby'        => 'post_title',
                    'order'          => 'ASC',
                   //'orderby'        => 'CAST([title]  AS unsigned)',
                    'post_parent'    => $section->ID,
                ];

                $query  = new WP_Query( $argss );
                $wdvdocsss   = $query->get_posts();
                $articles = get_page_children( $section->ID, $wdvdocsss );

                $numerictitles=array();

                foreach ( $articles as $article ){
                 $numerictitle=$article->post_title;
                 $numerictitle= preg_replace("/[^0-9.]/", '', $numerictitle);
                 $article->numbtitle = floatval($numerictitle);
              
                 array_push($numerictitles, floatval($numerictitle));
                }
                //and now sort it
                sort($numerictitles);
                usort($articles, function($obj1, $obj2) {
                    if ($obj1->numbtitle == $obj2->numbtitle) {
                        return 0;
                    }
                    return $obj1->numbtitle < $obj2->numbtitle ? -1 : 1;
                });
        ?>

          <ul class="sidepanel-list">
            <?php foreach ( $articles as $article ) { ?>
              <li>
              <?php $articlelink="#".$article->post_title; ?>
              <a href="<?php echo $articlelink; ?>" onclick="closeNav()">
              <?php echo $article->post_title; ?>
              </a>
              </li>
            <?php } ?>
          </ul>

        </li>
      <?php  } ?>
      </ul>


    </div>







 <button class="openbtn" onclick="openNav()">☰ Menu</button>
  <div id="wdv-main">  

    <h2 id="top" class="alt"><?php echo $wdvdoc->post_title; ?></h2>

  <div class="carrentdoc"><?php echo $wdvdoc->post_content; ?></div>

    <ul class="single-shortcode">
            
            <div class="child">
              <?php foreach ( $sections as $section ) { ?>
               <li class="add-child">
                 <?php $contentlink=$section->post_title; ?>
                    <h3 id="<?php echo $contentlink; ?>"><?php echo $section->post_title; ?> - <a class="top" href="#top">top</a></h3>
                    <div class="childcontent"><?php echo $section->post_content; ?></div>

        <?php
                $argss = [
                    'post_type'      => 'wdvdocs',
                    'posts_per_page' => -1,
                    'orderby'        => 'post_title',
                    'order'          => 'ASC',
                    //'orderby'        => 'CAST([title]  AS unsigned)',
                    'post_parent'    => $section->ID,
                ];

                $query  = new WP_Query( $argss );
                $wdvdocsss   = $query->get_posts();
                $articles = get_page_children( $section->ID, $wdvdocsss );


                $numerictitles=array();

                foreach ( $articles as $article ){
                 $numerictitle=$article->post_title;
                 $numerictitle= preg_replace("/[^0-9.]/", '', $numerictitle);

                 $article->numbtitle = floatval($numerictitle);
              
                 array_push($numerictitles, floatval($numerictitle));

                }
                //and now sort it
                sort($numerictitles);
                usort($articles, function($obj1, $obj2) {
                    if ($obj1->numbtitle == $obj2->numbtitle) {
                        return 0;
                    }
                    return $obj1->numbtitle < $obj2->numbtitle ? -1 : 1;
                });




        ?>
               <ul class="single-shortcode">
                 <?php foreach ( $articles as $article ) { ?>
                  <li class="add-article">
                    <?php $contentarticlelink=$article->post_title; ?>
                  <h3 id="<?php echo $contentarticlelink; ?>"><?php echo $article->post_title; ?> - <a class="top" href="#top">top</a></h3>
                  <div class="childcontent"><?php echo $article->post_content; ?></div>
                  </li>
                <?php  } ?>

                </ul>

                </li>
              <?php } ?>
            </div>

        </ul>
 
  </div>

  </div>
  <?php }