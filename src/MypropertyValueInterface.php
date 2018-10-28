<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Myproperty entity.
 * @ingroup myproperty
 */
interface MypropertyValueInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the myproperty_value creation timestamp.
   *
   * @return int
   *   Creation timestamp of the myproperty_value.
   */
  public function getCreatedTime();



  /**
   * Gets the property_id.
   *
   * @return int
   *   property_id of the myproperty_value.
   */
  public function getPropertyId();

  /**
   * Sets the myproperty_value property_id.
   *
   * @param int $property_id
   *   The myproperty_value property_id.
   *
   * @return \Drupal\myproperty\MypropertyValueInterface
   *   The called myproperty_value entity.
   */
  public function setPropertyId($property_id);



  /**
   * Gets the myproperty.
   *
   * @return \Drupal\myproperty\MypropertyInterface
   *   myproperty of the myproperty_value.
   */
  public function getMyproperty();

  /**
   * Sets the myproperty_value myproperty.
   *
   * @param \Drupal\myproperty\MypropertyInterface $myproperty
   *   The myproperty_value myproperty.
   *
   * @return \Drupal\myproperty\MypropertyValueInterface
   *   The called myproperty_value entity.
   */
  public function setMyproperty($myproperty);


  /**
   * Gets the name.
   *
   * @return string
   *   name of the myproperty_value.
   */
  public function getName();

  /**
   * Sets the myproperty_value name.
   *
   * @param string $name
   *   The myproperty_value name.
   *
   * @return \Drupal\myproperty\MypropertyValueInterface
   *   The called myproperty_value entity.
   */
  public function setName($name);
    
    
}



