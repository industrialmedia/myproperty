<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityStorageInterface;


/**
 * Provides a list controller for myproperty_value entity.
 * @ingroup myproperty
 */
class MypropertyValueListBuilder extends EntityListBuilder {


  protected $property_id;


  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage) {
    parent::__construct($entity_type, $storage);
    $this->property_id = \Drupal::request()->attributes->get('myproperty');
  }


  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * @return array
   *   An array of entity IDs.
   */
  protected function getEntityIds() {
    $query = $this->getStorage()->getQuery()
      ->condition('property_id', $this->property_id)
      ->sort('weight');
    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }


  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $myproperty) {
    /* @var $myproperty \Drupal\myproperty\Entity\Myproperty */
    $row = [];
    $row['name'] = $myproperty->getName();
    return $row + parent::buildRow($myproperty);
  }

}
