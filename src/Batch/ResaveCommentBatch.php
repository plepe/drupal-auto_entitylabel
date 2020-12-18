<?php

namespace Drupal\auto_entitylabel\Batch;

use Drupal\comment\Entity\Comment;

/**
 * Class ResaveCommentBatch
 *
 * @package Drupal\auto_entitylabel\Batch
 */
class ResaveCommentBatch {

  public static function batchOperation(array $chunk, array &$context) {
    foreach ($chunk as $cid) {
      /** @var \Drupal\comment\Entity\Comment $comment */
      $comment = Comment::load($cid);
      $comment->save();
      $context['results'][] = $cid;
    }
  }

  public static function batchFinished($success, array $results, array $operations) {
    $messenger = \Drupal::messenger();

    if ($success) {
      $messenger->addMessage(t('Resaved @count comments.', [
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
