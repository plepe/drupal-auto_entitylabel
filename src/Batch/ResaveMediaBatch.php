<?php

namespace Drupal\auto_entitylabel\Batch;

use Drupal\media\Entity\Media;

/**
 * Class ResaveMediaBatch
 *
 * @package Drupal\auto_entitylabel\Batch
 */
class ResaveMediaBatch {

  public static function batchOperation(array $chunk, array &$context) {
    foreach ($chunk as $mid) {
      /** @var \Drupal\media\Entity\Media $media */
      $media = Media::load($mid);
      $media->save();
      $context['results'][] = $mid;
    }
  }

  public static function batchFinished($success, array $results, array $operations) {
    $messenger = \Drupal::messenger();

    if ($success) {
      $messenger->addMessage(t('Resaved @count media.', [
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
