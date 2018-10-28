<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;


class MypropertyStorage extends SqlContentEntityStorage implements MypropertyStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getMypropertyValueIdsByMypropertyIds($ids) {
    /* @var \Drupal\Core\Database\Query\Select $query */
    $query = $this->database->select('myproperty_value_field_data', 'pv');
    $query->addField('pv', 'id', 'id');
    $query->condition('pv.property_id', $ids, 'IN');
    $result = $query->execute();
    return $result->fetchCol();
  }

}
