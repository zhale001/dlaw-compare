<?php
/**
 * @file
 * twitter_pod.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function twitter_pod_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'twitter_import';
  $strongarm->value = 0;
  $export['twitter_import'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'twitter_post_default_format';
  $strongarm->value = '!title !tinyurl';
  $export['twitter_post_default_format'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'twitter_post_default_value';
  $strongarm->value = 0;
  $export['twitter_post_default_value'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'twitter_post_types';
  $strongarm->value = array(
    'page' => 'page',
    'contact' => 0,
    'panel' => 0,
  );
  $export['twitter_post_types'] = $strongarm;

  return $export;
}
