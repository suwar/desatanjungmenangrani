<?php
if (!defined('ABSPATH'))
  exit();



class PGBlockImage
{
  function __construct()
  {
    add_action('init', array($this, 'register_scripts'));
    add_action('wp_enqueue_scripts', array($this, 'front_scripts'));
  }


  // loading src files in the gutenberg editor screen
  function register_scripts()
  {
    //wp_register_style('editor_style', post_grid_plugin_url . 'includes/blocks/image/index.css');
    // wp_register_script('fslightbox', post_grid_plugin_url . 'includes/blocks/image/fslightbox.js', array());



    register_block_type(
      post_grid_plugin_dir . 'build/blocks/image/block.json',
      array(

        'render_callback' => array($this, 'theHTML'),

      )
    );
  }

  function front_script($attributes)
  {
    wp_register_script('lazyLoad', post_grid_plugin_url . 'includes/blocks/image/lazy-load.js', [], '', true);
  }

  function front_scripts($attributes)
  {
  }




  function front_style($attributes)
  {
  }





  // front-end output from the gutenberg editor 
  function theHTML($attributes, $content, $block)
  {


    wp_register_script('fslightbox', post_grid_plugin_url . 'includes/blocks/image/fslightbox.js', [], '', true);
    wp_register_script('pgimage_front_script', post_grid_plugin_url . 'includes/blocks/image/front-scripts.js', [], '', true);
    wp_register_script('lazyLoad', post_grid_plugin_url . 'includes/blocks/image/lazy-load.js', [], '', true);



    $galleryLightbox = isset($block->context['post-grid/lightbox']) ? $block->context['post-grid/lightbox'] : null;
    $lightbox = ($galleryLightbox == null) ? $attributes['lightbox'] : $galleryLightbox;
    $lightboxOptions = isset($lightbox['options']) ? $lightbox['options'] : [];

    $lightboxEnable = isset($lightboxOptions['enable']) ? $lightboxOptions['enable'] : false;

    if (has_block('post-grid/image')) {

      wp_enqueue_script('lazyLoad');

      if ($lightboxEnable == true) {
        wp_enqueue_script('fslightbox');
        wp_enqueue_script('pgimage_front_script');
      }
    }




    global $postGridCss;

    global $postGridCssY;

    $post_ID = isset($block->context['postId']) ? $block->context['postId'] : '';

    $post_url = get_the_permalink($post_ID);

    $blockId = isset($attributes['blockId']) ? $attributes['blockId'] : '';
    $blockAlign = isset($attributes['align']) ? 'align' . $attributes['align'] : '';
    $galleryId = isset($block->context['post-grid/galleryId']) ? $block->context['post-grid/galleryId'] : $blockId;




    $wrapper = isset($attributes['wrapper']) ? $attributes['wrapper'] : [];
    $wrapperOptions = isset($wrapper['options']) ? $wrapper['options'] : [];

    $wrapperClass = isset($wrapperOptions['class']) ? $wrapperOptions['class'] : '';

    $wrapperTag = isset($wrapperOptions['tag']) ? $wrapperOptions['tag'] : 'h2';
    $useAsBackground = isset($wrapperOptions['useAsBackground']) ? $wrapperOptions['useAsBackground'] : 'no';





    $featuredImage = isset($attributes['image']) ? $attributes['image'] : [];
    $featuredImageOptions = isset($featuredImage['options']) ? $featuredImage['options'] : [];

    $featuredImageSrcType = isset($featuredImageOptions['imgSrcType']) ? $featuredImageOptions['imgSrcType'] : '';
    $featuredImageSrcMetaKey = isset($featuredImageOptions['imgSrcMetaKey']) ? $featuredImageOptions['imgSrcMetaKey'] : '';
    $featuredImageSrcMetaKeyType = isset($featuredImageOptions['imgSrcMetaKeyType']) ? $featuredImageOptions['imgSrcMetaKeyType'] : '';

    $featuredImagesrcUrl = isset($featuredImageOptions['srcUrl']) ? $featuredImageOptions['srcUrl'] : '';

    $featuredImagesrcId = isset($featuredImageOptions['srcId']) ? $featuredImageOptions['srcId'] : '';
    $featuredImageLinkTo = isset($featuredImageOptions['linkTo']) ? $featuredImageOptions['linkTo'] : '';
    $featuredImageLinkToMetaKey = isset($featuredImageOptions['linkToMetaKey']) ? $featuredImageOptions['linkToMetaKey'] : '';

    $featuredImageAltTextSrc = isset($featuredImageOptions['altTextSrc']) ? $featuredImageOptions['altTextSrc'] : 'imgAltText';
    $featuredImageTitleTextSrc = isset($featuredImageOptions['titleTextSrc']) ? $featuredImageOptions['titleTextSrc'] : 'imgTitle';

    $featuredImageAltTextCustom = isset($featuredImageOptions['altTextCustom']) ? $featuredImageOptions['altTextCustom'] : '';
    $featuredImageAltTextMetaKey = isset($featuredImageOptions['altTextMetaKey']) ? $featuredImageOptions['altTextMetaKey'] : '';

    $featuredImageIsLink = isset($featuredImageOptions['isLink']) ? $featuredImageOptions['isLink'] : true;
    $linkTarget = isset($featuredImageOptions['linkTarget']) ? $featuredImageOptions['linkTarget'] : '_blank';
    $customUrl = isset($featuredImageOptions['customUrl']) ? $featuredImageOptions['customUrl'] : '';
    $linkTocustomUrl = isset($featuredImageOptions['linkTocustomUrl']) ? $featuredImageOptions['linkTocustomUrl'] : '';


    $linkAttr = isset($featuredImageOptions['linkAttr']) ? $featuredImageOptions['linkAttr'] : [];
    $rel = isset($featuredImageOptions['rel']) ? $featuredImageOptions['rel'] : '';
    $size = isset($featuredImageOptions['size']) ? $featuredImageOptions['size'] : '';

    $utmTracking = isset($attributes['utmTracking']) ? $attributes['utmTracking'] : '';
    $utmTrackingEnable = isset($utmTracking['enable']) ? $utmTracking['enable'] : '';
    $utmTrackingID = isset($utmTracking['id']) ? $utmTracking['id'] : '';
    $utmTrackingSource = isset($utmTracking['source']) ? $utmTracking['source'] : '';
    $utmTrackingMedium = isset($utmTracking['medium']) ? $utmTracking['medium'] : '';
    $utmTrackingCampaign = isset($utmTracking['campaign']) ? $utmTracking['campaign'] : '';
    $utmTrackingTerm = isset($utmTracking['term']) ? $utmTracking['term'] : '';
    $utmTrackingContent = isset($utmTracking['content']) ? $utmTracking['content'] : '';



    $lazyLoad = isset($featuredImageOptions['lazy']) ? $featuredImageOptions['lazy'] : '';
    $lazyLoadSrc = isset($featuredImageOptions['lazySrc']) ? $featuredImageOptions['lazySrc'] : '';
    // $lazyLoad = isset($featuredImageOptions['lazy']) ? $featuredImageOptions['lazy'] : '';


    $blockCssY = isset($attributes['blockCssY']) ? $attributes['blockCssY'] : [];
    $postGridCssY[] = $blockCssY['items'];



    $linkAttrStr = '';



    if (!empty($linkAttr))
      foreach ($linkAttr as $attr) {

        if (!empty($attr['val']))
          $linkAttrStr .= esc_attr($attr['id']) . '=' . esc_attr($attr['val']) . ' ';
      }



    $post_title = get_the_title($post_ID);

    //$thumb_id = get_post_thumbnail_id($post_ID);
    $thumb_id = $featuredImagesrcId;



    $image_src = wp_get_attachment_image_src($thumb_id);
    $attachment_url = wp_get_attachment_url($thumb_id);
    $attachment_post = get_post($thumb_id);

    $image_srcset = wp_get_attachment_image_srcset($thumb_id);
    $attachment_metadata = wp_get_attachment_metadata($thumb_id);

    if ($featuredImageSrcType == 'customField') {

      if ($featuredImageSrcMetaKeyType == 'ID') {

        $thumb_id = get_post_meta($post_ID, $featuredImageSrcMetaKey, true);
        //echo '<pre>' . //var_export($thumb_id, true) . '</pre>';


        $attachment_url = wp_get_attachment_url($thumb_id);
        $attachment_post = get_post($thumb_id);

        $image_srcset = wp_get_attachment_image_srcset($thumb_id);
      } else {
        $attachment_url = get_post_meta($post_ID, $featuredImageSrcMetaKey, true);
      }
    } elseif ($featuredImageSrcType == 'customUrl') {
      $attachment_url = $featuredImagesrcUrl;
    } elseif ($featuredImageSrcType == 'imgId') {
      $attachment_url = wp_get_attachment_url($featuredImagesrcId);
    }

    $linkUrl = "";

    if ($featuredImageLinkTo == 'postUrl') {

      $linkUrl = get_permalink($post_ID);
    } else if ($featuredImageLinkTo == 'customField') {

      $linkUrl = get_post_meta($post_ID, $featuredImageLinkToMetaKey, true);
    } else if ($featuredImageLinkTo == 'authorUrl') {
      $author_id = get_post_field('post_author', $post_ID);
      $user = get_user_by('ID', $author_id);
      $linkUrl = $user->user_url;
    } else if ($featuredImageLinkTo == 'authorLink') {
      $author_id = get_post_field('post_author', $post_ID);
      $linkUrl = get_the_author_link($author_id);
    } else if ($featuredImageLinkTo == 'homeUrl') {
      $linkUrl = get_bloginfo('url');
    } else if ($featuredImageLinkTo == 'customUrl') {
      $linkUrl = $linkTocustomUrl;
    }

    if ($utmTrackingEnable == true) {
      $utmValue = [];

      if (!empty($utmTrackingID))
        $utmValue['utm_id'] = $utmTrackingID;
      if (!empty($utmTrackingSource))
        $utmValue['utm_source'] = $utmTrackingSource;
      if (!empty($utmTrackingMedium))
        $utmValue['utm_medium'] = $utmTrackingMedium;
      if (!empty($utmTrackingCampaign))
        $utmValue['utm_campaign'] = $utmTrackingCampaign;
      if (!empty($utmTrackingTerm))
        $utmValue['utm_term'] = $utmTrackingTerm;
      if (!empty($utmTrackingContent))
        $utmValue['utm_content'] = $utmTrackingContent;

      $utmUrl = add_query_arg($utmValue, $linkUrl);

      $linkUrl = $utmUrl;
    }

    if ($lightboxEnable == true) {

      $linkUrl = $attachment_url;
    }


    $altText = '';

    if ($featuredImageAltTextSrc == 'imgAltText') {

      $altText = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
    } else if ($featuredImageAltTextSrc == 'imgCaption') {

      $altText = $attachment_post->post_excerpt;
    } else if ($featuredImageAltTextSrc == 'imgDescription') {
      $altText = $attachment_post->post_content;
    } else if ($featuredImageAltTextSrc == 'imgTitle') {
      $altText = get_the_title($thumb_id);
    } else if ($featuredImageAltTextSrc == 'imgSlug') {
      $altText = get_post_field('post_name', $post_ID);
    } else if ($featuredImageAltTextSrc == 'postTitle') {
      $altText = get_the_title($post_ID);
    } else if ($featuredImageAltTextSrc == 'excerpt') {
      $altText = get_the_excerpt($post_ID);
    } else if ($featuredImageAltTextSrc == 'postSlug') {
      $altText = get_the_excerpt($post_ID);
    } else if ($featuredImageAltTextSrc == 'customField') {
      $altText = get_post_meta($post_ID, $featuredImageAltTextMetaKey, true);
    } else if ($featuredImageAltTextSrc == 'custom') {
      $altText = $featuredImageAltTextCustom;
    }


    $titleText = '';

    if ($featuredImageTitleTextSrc == 'imgAltText') {

      $titleText = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
    } else if ($featuredImageTitleTextSrc == 'imgCaption') {

      $titleText = $attachment_post->post_excerpt;
    } else if ($featuredImageTitleTextSrc == 'imgDescription') {
      $titleText = $attachment_post->post_content;
    } else if ($featuredImageTitleTextSrc == 'imgTitle') {
      $titleText = get_the_title($thumb_id);
    } else if ($featuredImageTitleTextSrc == 'imgSlug') {
      $titleText = get_post_field('post_name', $post_ID);
    } else if ($featuredImageTitleTextSrc == 'postTitle') {
      $titleText = get_the_title($post_ID);
    } else if ($featuredImageTitleTextSrc == 'excerpt') {
      $titleText = get_the_excerpt($post_ID);
    } else if ($featuredImageTitleTextSrc == 'postSlug') {
      $titleText = get_the_excerpt($post_ID);
    } else if ($featuredImageTitleTextSrc == 'customField') {
      $titleText = get_post_meta($post_ID, $featuredImageAltTextMetaKey, true);
    } else if ($featuredImageTitleTextSrc == 'custom') {
      $titleText = $featuredImageAltTextCustom;
    }




    if (strlen($lazyLoadSrc) === 0) {
      $lazyLoadSrc = post_grid_plugin_url . "/assets/images/loading.gif";
    }


    if ($lazyLoad == true) {

      $dataSrc = $attachment_url;



      // $lazy_img_src = $lazyLoadSrc;
      $attachment_url = $lazyLoadSrc;

      $lazy = "lazy";
    } else {
      // $attachment_url_img = $attachment_url;
      // $attachment_url = $attachment_url_img;


      $lazy = "eager";
      $dataSrc = "";
    }



    $obj['id'] = $post_ID;
    $obj['type'] = 'post';



    $wrapperClass = parse_css_class($wrapperClass, $obj);


    ob_start();



    if (!empty($wrapperTag) && $useAsBackground == 'no') :

?>
      <<?php echo esc_attr($wrapperTag); ?> class="
                    <?php echo esc_attr($blockId); ?>
                    <?php echo esc_attr($wrapperClass); ?>">
        <?php if (!empty($featuredImageLinkTo)) : ?>
          <a <?php if ($lightboxEnable == true) : ?> data-fslightbox="<?php echo esc_attr($galleryId); ?>" <?php endif; ?> href=" <?php echo (!empty($linkUrl)) ? esc_url_raw($linkUrl) : esc_url_raw($post_url); ?>" rel="<?php echo esc_attr($rel); ?>" target="<?php echo esc_attr($linkTarget); ?>" <?php echo esc_attr($linkAttrStr); ?>>

            <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" <?php if ($lazyLoad == true) : ?> data-src="<?php echo esc_url_raw($dataSrc); ?>" loading="<?php echo $lazy ?>" <?php endif; ?> <?php if ($lazyLoad == false) : ?> srcset=" <?php echo esc_attr($image_srcset); ?>" <?php endif; ?> alt="<?php echo esc_attr($altText); ?>" title="<?php echo esc_attr($titleText); ?>" />

          </a>
        <?php else : ?>

          <?php if ($lightboxEnable == true) : ?>
            <a href="<?php if ($lazyLoad == true) : ?><?php echo esc_url_raw($dataSrc); ?><?php endif; ?><?php if ($lazyLoad == false) : ?><?php echo esc_url_raw($attachment_url); ?><?php endif; ?>" data-fslightbox="<?php echo esc_attr($galleryId); ?>">
              <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" <?php if ($lazyLoad == true) : ?> data-src="<?php echo esc_url_raw($dataSrc); ?>" loading="<?php echo $lazy ?>" <?php endif; ?> <?php if ($lazyLoad == false) : ?> srcset=" <?php echo esc_attr($image_srcset); ?>" <?php endif; ?> alt="<?php echo esc_attr($altText); ?>" title="<?php echo esc_attr($titleText); ?>" />
            </a>
          <?php else : ?>

            <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" <?php if ($lazyLoad == true) : ?> data-src="<?php echo esc_url_raw($dataSrc); ?>" loading="<?php echo $lazy ?>" <?php endif; ?> <?php if ($lazyLoad == false) : ?> srcset=" <?php echo esc_attr($image_srcset); ?>" <?php endif; ?> alt="<?php echo esc_attr($altText); ?>" title="<?php echo esc_attr($titleText); ?>" />
          <?php endif; ?>

        <?php endif; ?>
      </<?php echo esc_attr($wrapperTag); ?>>
    <?php

    endif;

    if (empty($wrapperTag) && $useAsBackground == 'no') :

    ?>

      <?php if (!empty($featuredImageLinkTo)) : ?>
        <a class=" <?php echo esc_attr($blockId); ?>" href="<?php echo (!empty($linkUrl)) ? esc_url_raw($linkUrl) : esc_url_raw($post_url); ?>" rel=" <?php echo esc_attr($rel); ?>" target="<?php echo esc_attr($linkTarget); ?>" <?php if ($lightboxEnable == true) : ?> data-fslightbox="<?php echo esc_attr($galleryId); ?>" <?php endif; ?>>


          <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" <?php if ($lazyLoad == true) : ?> data-src="<?php echo esc_url_raw($dataSrc); ?>" loading="<?php echo $lazy ?>" <?php endif; ?> <?php if ($lazyLoad == false) : ?> srcset=" <?php echo esc_attr($image_srcset); ?>" <?php endif; ?> alt="<?php echo esc_attr($altText); ?>" title="<?php echo esc_attr($titleText); ?>" />

        </a>
      <?php else : ?>

        <?php if ($lightboxEnable == true) : ?>
          <a class=" <?php echo esc_attr($blockId); ?>" href="<?php echo (!empty($linkUrl)) ? esc_url_raw($linkUrl) : esc_url_raw($post_url); ?>" data-fslightbox=" <?php echo esc_attr($galleryId); ?>"> <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" srcset="<?php echo esc_attr($image_srcset); ?>" alt="<?php echo esc_attr($altText); ?>" title=" <?php echo esc_attr($titleText); ?>" /> </a>
        <?php else : ?>
          <img <?php echo esc_attr($linkAttrStr); ?> src="<?php echo esc_url_raw($attachment_url); ?>" <?php if ($lazyLoad == true) : ?> data-src="<?php echo esc_url_raw($dataSrc); ?>" loading="<?php echo $lazy ?>" <?php endif; ?> <?php if ($lazyLoad == false) : ?> srcset=" <?php echo esc_attr($image_srcset); ?>" <?php endif; ?> alt="<?php echo esc_attr($altText); ?>" title="<?php echo esc_attr($titleText); ?>" />

        <?php endif; ?>

      <?php endif; ?>

    <?php

    endif;



    if (!empty($wrapperTag) && $useAsBackground == 'yes') :

    ?>

      <?php if (!empty($featuredImageLinkTo)) : ?>
        <a href=" <?php echo (!empty($linkUrl)) ? esc_url_raw($linkUrl) : esc_url_raw($post_url); ?>" rel="<?php echo esc_attr($rel); ?>" target="<?php echo esc_attr($linkTarget); ?>" <?php echo esc_attr($linkAttrStr); ?>>
          <<?php echo esc_attr($wrapperTag); ?> class="
                            <?php echo esc_attr($blockId); ?>">

          </<?php echo esc_attr($wrapperTag); ?>>
        </a>
      <?php else : ?>

        <<?php echo esc_attr($wrapperTag); ?> class="
                          <?php echo esc_attr($blockId); ?>" <?php echo esc_attr($linkAttrStr); ?>>

        </<?php echo esc_attr($wrapperTag); ?>>
      <?php endif; ?>

    <?php

    endif;


    ?>









<?php return ob_get_clean();
  }
}

$PGBlockImage = new PGBlockImage();
