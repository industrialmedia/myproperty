<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Myproperty entity.
 * @ingroup myproperty
 */
interface MypropertyInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {



  /**
   * Gets the myproperty creation timestamp.
   *
   * @return int
   *   Creation timestamp of the myproperty.
   */
  public function getCreatedTime();


  /**
   * Gets the weight.
   *
   * @return int
   *   weight of the myproperty.
   */
  public function getWeight();


  /**
   * Sets the myproperty weight.
   *
   * @param int $weight
   *   The myproperty weight.
   *
   * @return \Drupal\myproperty\MypropertyInterface
   *   The called myproperty entity.
   */
  public function setWeight($weight);

  /**
   * Gets the name.
   *
   * @return string
   *   name of the myproperty.
   */
  public function getName();

  /**
   * Sets the myproperty name.
   *
   * @param string $name
   *   The myproperty name.
   *
   * @return \Drupal\myproperty\MypropertyInterface
   *   The called myproperty entity.
   */
  public function setName($name);



  /**
   * Gets the machine_name.
   *
   * @return string
   *   machine_name of the myproperty.
   */
  public function getMachineName();

  /**
   * Sets the myproperty machine_name.
   *
   * @param string $machine_name
   *   The myproperty machine_name.
   *
   * @return \Drupal\myproperty\MypropertyInterface
   *   The called myproperty entity.
   */
  public function setMachineName($machine_name);



  /**
   * Load the myproperty by machine_name.
   *
   * @param string $machine_name
   *   The myproperty machine_name.
   *
   * @return \Drupal\myproperty\MypropertyInterface
   *   The called myproperty entity.
   */
  public static function loadByMachineName($machine_name);
  
}



