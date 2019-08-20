<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;


/**
 * Provides a list controller for myproperty entity.
 * @ingroup myproperty
 */
class MypropertyListBuilder extends EntityListBuilder {


  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * @return array
   *   An array of entity IDs.
   */
  protected function getEntityIds() {
    $query = $this->getStorage()->getQuery()
      ->sort('weight');

    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }


  /**
   * Gets this list's default operations.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity the operations are for.
   *
   * @return array
   *   The array structure is identical to the return value of
   *   self::getOperations().
   */
  protected function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);
    if ($entity->access('view') && $entity->hasLinkTemplate('list-property-values')) {
      $operations['list_property_values'] = [
        'title' => $this->t('List property values'),
        'weight' => -10,
        'url' => $this->ensureDestination($entity->toUrl('list-property-values')),
      ];
    }
    return $operations;
  }


  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the myproperty list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['machine_name'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $myproperty) {
    /* @var $myproperty \Drupal\myproperty\Entity\Myproperty */
    $row = [];
    $row['name'] = $myproperty->getName();
    $row['machine_name'] = $myproperty->getMachineName();
    return $row + parent::buildRow($myproperty);
  }

}
