<?php
if (!defined('ABSPATH'))
  exit();



class PGBlockTermsQuery
{
  function __construct()
  {
    add_action('init', array($this, 'register_scripts'));
    add_action('wp_enqueue_scripts', array($this, 'front_scripts'));
  }


  // loading src files in the gutenberg editor screen
  function register_scripts()
  {



    register_block_type(
      post_grid_plugin_dir . 'build/blocks/terms-query/block.json',
      array(
        'title' => "Terms Query",
        'render_callback' => array($this, 'theHTML'),


      )
    );
  }

  function front_scripts($attributes)
  {
    // wp_register_script('pgpostquery_front_script', post_grid_plugin_url . 'includes/blocks/post-query/front-scripts.js', []);
    // wp_register_style('pgpostquery_front_style', post_grid_plugin_url . 'includes/blocks/post-query/index.css');
    if (has_block('post-grid/post-query')) {

      //wp_enqueue_script('pgpostquery_front_script');
      //wp_enqueue_style('pgpostquery_front_style');
    }
  }
  function front_style($attributes)
  {
  }




  // front-end output from the gutenberg editor 
  function theHTML($attributes, $content, $block)
  {
    wp_enqueue_style('font-awesome-5');


    global $postGridCss;

    global $postGridCssY;
    global $postGridScriptData;
    global $PGPostQuery;
    global $PGBlockPostQuery;

    $block_instance = $block->parsed_block;

    //var_dump($block_instance);


    $blockId = isset($attributes['blockId']) ? $attributes['blockId'] : '';
    $blockAlign = isset($attributes['align']) ? 'align' . $attributes['align'] : '';

    $postGridId = isset($block->context['post-grid/postGridId']) ? $block->context['post-grid/postGridId'] : '';




    $wrapper = isset($attributes['wrapper']) ? $attributes['wrapper'] : [];
    $wrapperOptions = isset($wrapper['options']) ? $wrapper['options'] : [];
    $wrapperClass = isset($wrapperOptions['class']) ? $wrapperOptions['class'] : '';


    $itemsWrap = isset($attributes['itemsWrap']) ? $attributes['itemsWrap'] : [];
    $itemsWrapOptions = isset($itemsWrap['options']) ? $itemsWrap['options'] : [];
    $itemsWrapExcluded = isset($itemsWrapOptions['excludedWrapper']) ? $itemsWrapOptions['excludedWrapper'] : false;

    /*#######itemWrap######*/
    $itemWrap = isset($attributes['itemWrap']) ? $attributes['itemWrap'] : [];
    $itemWrapOptions = isset($itemWrap['options']) ? $itemWrap['options'] : [];
    $itemWrapTag = isset($itemWrapOptions['tag']) ? $itemWrapOptions['tag'] : 'div';
    $itemWrapClass = isset($itemWrapOptions['class']) ? $itemWrapOptions['class'] : 'item';
    $itemWrapCounterClass = isset($itemWrapOptions['counterClass']) ? $itemWrapOptions['counterClass'] : false;
    $itemWrapTermsClass = isset($itemWrapOptions['termsClass']) ? $itemWrapOptions['termsClass'] : false;
    $itemWrapOddEvenClass = isset($itemWrapOptions['oddEvenClass']) ? $itemWrapOptions['oddEvenClass'] : false;


    /*#########$noPostsWrap#########*/
    $noPostsWrap = isset($attributes['noPostsWrap']) ? $attributes['noPostsWrap'] : [];
    $noPostsWrapOptions = isset($noPostsWrap['options']) ? $noPostsWrap['options'] : [];

    $grid = isset($attributes['grid']) ? $attributes['grid'] : [];
    $gridOptions = isset($grid['options']) ? $grid['options'] : [];
    $gridOptionsItemCss = isset($gridOptions['itemCss']) ? $gridOptions['itemCss'] : [];


    /*#######pagination######*/
    $pagination = isset($attributes['pagination']) ? $attributes['pagination'] : [];
    $paginationOptions = isset($pagination['options']) ? $pagination['options'] : [];
    $paginationType = isset($paginationOptions['type']) ? $paginationOptions['type'] : 'none';




    $queryArgs = isset($attributes['queryArgs']) ? $attributes['queryArgs'] : [];


    $parsed_block = isset($block->parsed_block) ? $block->parsed_block : [];
    $innerBlocks = isset($parsed_block['innerBlocks']) ? $parsed_block['innerBlocks'] : [];



    $postGridScriptData[$postGridId]['queryArgs'] = isset($queryArgs['items']) ? $queryArgs['items'] : [];
    $postGridScriptData[$postGridId]['layout']['rawData'] = serialize_blocks($innerBlocks);



    $query_args = post_grid_parse_query_terms(isset($queryArgs['items']) ? $queryArgs['items'] : []);
    // $query_args = apply_filters("pgb_post_query_prams", $query_args, ["blockId" => $blockId]);

    // $query_args = apply_filters("pgb_post_query_prams", $query_args, ["blockId" => $blockId]);

    //echo var_export($query_args, true);


    


    $posts = [];
    $responses = [];


    // $PGPostQuery = new WP_Query($query_args);

    $terms = get_terms($query_args);

    // var_dump($innerBlocks);

    $blockArgs = [
      'blockId' => $blockId,
      'noPosts' => false
    ];




    ob_start();

?>


<?php
    if (!$itemsWrapExcluded) :
    ?>
<div class="loop-loading"></div>
<div class="<?php echo esc_attr($blockId); ?> pg-post-query items-loop"
  id="items-loop-<?php echo esc_attr($blockId); ?>" blockArgs="<?php echo esc_attr(json_encode($blockArgs)); ?>">
  <?php
    endif;
      ?>
  <?php
      if ($terms) :

        $counter = 1;

      
        $the_query = new WP_Term_Query($query_args);
        foreach ($the_query->get_terms() as $term) {





          $term_id = isset($term->term_id) ? $term->term_id : "";
          $taxonomy = isset($term->taxonomy) ? $term->taxonomy : "";


          $blocks = $innerBlocks;


          if ($counter % 2 == 0) {
            $odd_even_class = 'even';
          } else {
            $odd_even_class = 'odd';
          }
          $html = '';

          $filter_block_context = static function ($context) use ($term_id, $taxonomy) {
            $context['taxonomy'] = $taxonomy;
            $context['term_id']   = $term_id;
            $context['xyz']   = $term_id;
            return $context;
          };

          //var_dump($filter_block_context);
          add_filter('render_block_context', $filter_block_context, 1);


          foreach ($blocks as $block) {


            //look to see if your block is in the post content -> if yes continue past it if no then render block as normal
            $html .= render_block($block);
          }

          remove_filter('render_block_context', $filter_block_context, 1);







      ?>
  <<?php echo esc_html($itemWrapTag); ?> class="
            <?php echo esc_attr($itemWrapClass); ?>
            <?php ?>
            <?php if ($itemWrapCounterClass) {
              echo esc_attr("item-" . $counter);
            } ?>
            <?php if ($itemWrapOddEvenClass) {
              echo esc_attr($odd_even_class);
            } ?> ">
    <?php echo wp_kses_post($html);
            ?>
  </<?php echo esc_html($itemWrapTag); ?>>
  <?php
          $counter++;
        }

      endif;

      ?>

  <?php
      if (!$itemsWrapExcluded) : ?>
</div>
<?php
      endif; ?>
<?php return ob_get_clean();
  }
}

$BlockPostGrid = new PGBlockTermsQuery();