<?php

namespace Drupal\site_api\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

/**
 * PageJsonController to display JSON format of node.
 */
class PageJsonController extends ControllerBase {

  public function get_page_json($siteapikey, $nid) {
    if (!empty($nid)) {
      $node = Node::load($nid)->toArray();
      return new JsonResponse($node, 200, ['Content-Type' => 'application/json']);
    }
    return [];
  }

  /**
   * Checks access for this controller.
   */
  public function access($siteapikey, $nid) {
    $config = \Drupal::config('system.site');
    $storedKey = $config->get('siteapikey');
    if (!empty($nid)) {
      $node = Node::load($nid);
      if ($storedKey == 'No API Key yet' || $storedKey != $siteapikey || !is_numeric($nid) || $node->getType() != 'page') {
        // Return 403 Access Denied page.  
        return AccessResult::forbidden();
      }
    }
    return AccessResult::allowed();
  }

}
