<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityStorageInterface;


interface MypropertyStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets myproperty_value IDs of myproperty IDs.
   *
   * @param array $ids
   *   Array of myproperty IDs.
   * @return array
   *   Array of myproperty_value IDs.
   */
  public function getMypropertyValueIdsByMypropertyIds($ids);


}
