<?php

namespace Drupal\myproperty\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\myproperty\Entity\Myproperty;


class MypropertyController extends ControllerBase {

  public function listPropertyValuesTitle($myproperty) {
    $myproperty = Myproperty::load($myproperty);
    return $myproperty->getName() . ': ' . t('List property values');
  }

}