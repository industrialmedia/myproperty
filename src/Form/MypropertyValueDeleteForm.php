<?php

namespace Drupal\myproperty\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a myproperty entity.
 *
 * @ingroup myproperty
 */
class MypropertyValueDeleteForm extends ContentEntityDeleteForm {


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    /* @var \Drupal\myproperty\Entity\MypropertyValue $myproperty_value */
    $myproperty_value = $this->getEntity();
    $form_state->setRedirect('entity.myproperty.list_property_values', ['myproperty' => $myproperty_value->getPropertyId()]);
  }

}


