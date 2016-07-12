<?php
/**
 * @file
 * twitter_pod.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function twitter_pod_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'add authenticated twitter accounts'.
  $permissions['add authenticated twitter accounts'] = array(
    'name' => 'add authenticated twitter accounts',
    'roles' => array(
      'manager' => 'manager',
    ),
    'module' => 'twitter',
  );

  // Exported permission: 'add twitter accounts'.
  $permissions['add twitter accounts'] = array(
    'name' => 'add twitter accounts',
    'roles' => array(
      'manager' => 'manager',
    ),
    'module' => 'twitter',
  );

  // Exported permission: 'administer twitter accounts'.
  $permissions['administer twitter accounts'] = array(
    'name' => 'administer twitter accounts',
    'roles' => array(
      'manager' => 'manager',
    ),
    'module' => 'twitter',
  );

  // Exported permission: 'post to twitter'.
  $permissions['post to twitter'] = array(
    'name' => 'post to twitter',
    'roles' => array(
      'editor' => 'editor',
      'manager' => 'manager',
    ),
    'module' => 'twitter_post',
  );

  return $permissions;
}
