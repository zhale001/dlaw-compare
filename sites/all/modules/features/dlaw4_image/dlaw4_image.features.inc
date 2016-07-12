<?php
/**
 * @file
 * dlaw4_image.features.inc
 */

/**
 * Implements hook_image_default_styles().
 */
function dlaw4_image_image_default_styles() {
  $styles = array();

  // Exported image style: dlaw4_image.
  $styles['dlaw4_image'] = array(
    'label' => 'DLAW4 Image',
    'effects' => array(
      1 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 400,
          'height' => 225,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: hero.
  $styles['hero'] = array(
    'label' => 'Hero',
    'effects' => array(
      2 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 2000,
          'height' => 2000,
          'upscale' => 1,
        ),
        'weight' => 2,
      ),
    ),
  );

  return $styles;
}
