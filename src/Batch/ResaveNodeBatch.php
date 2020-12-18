<?php

namespace Drupal\auto_entitylabel\Batch;

use Drupal\node\Entity\Node;

/**
 * Class ResaveNodeBatch
 *
 * @package Drupal\auto_entitylabel\Batch
 */
class ResaveNodeBatch {

  public static function batchOperation(array $chunk, array &$context) {
    foreach ($chunk as $nid) {
      /** @var \Drupal\node\Entity\Node $node */
      $node = Node::load($nid);
      $node->save();
      $context['results'][] = $nid;
    }
  }

  public static function batchFinished($success, array $results, array $operations) {
    $messenger = \Drupal::messenger();

    if ($success) {
      $messenger->addMessage(t('Resaved @count nodes.', [
        '@count' => count($results),
      ]));
    }
    else {
      // An error occurred.
      // $operations contains the operations that remained unprocessed.
      $error_operation = reset($operations);
      $message = t('An error occurred while processing %error_operation with arguments: @arguments', [
        '%error_operation' => $error_operation[0],
        '@arguments' => print_r($error_operation[1], TRUE),
      ]);
      $messenger->addError($message);
    }
  }

}
