<?php

namespace Drupal\auto_entitylabel\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\NodeType;

/**
 * AutoEntityLabelController class.
 */
class AutoEntityLabelController extends ControllerBase {

  /**
   * Get Content Type Items.
   */
  public function getContentTypesItems() {

    $markup = '<ul class="admin-list">';

    $all_content_types = NodeType::loadMultiple();

    foreach ($all_content_types as $content_type_machine_name => $content_type) {

      $content_type_label = $content_type->label();

      $markup .= '<li><a href="/admin/structure/types/manage/' . $content_type_machine_name . '/auto-label"><span class="label">' . $content_type_label . '</span><div class="description">AUTOMATIC LABEL GENERATION FOR ' . strtoupper($content_type_label) . '</div></a></li>';
    }

    $markup .= '</ul>';

    $element = [
      '#markup' => $markup,
    ];
    return $element;
  }

}
