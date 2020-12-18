<?php

namespace Drupal\auto_entitylabel\Batch;

use Drupal\taxonomy\Entity\Term;

/**
 * Class ResaveTermBatch
 *
 * @package Drupal\auto_entitylabel\Batch
 */
class ResaveTermBatch {

  public static function batchOperation(array $chunk, array &$context) {
    foreach ($chunk as $tid) {
      /** @var \Drupal\taxonomy\Entity\Term $term */
      $term = Term::load($tid);
      $term->save();
      $context['results'][] = $tid;
    }
  }

  public static function batchFinished($success, array $results, array $operations) {
    $messenger = \Drupal::messenger();

    if ($success) {
      $messenger->addMessage(t('Resaved @count terms.', [
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
