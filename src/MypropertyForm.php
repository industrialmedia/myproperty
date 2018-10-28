<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the myproperty entity edit forms.
 *
 * @ingroup myproperty
 */
class MypropertyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    if ($status == SAVED_UPDATED) {
      $this->messenger()->addStatus($this->t('The property has been updated.'));
      $form_state->setRedirect('entity.myproperty.collection');
    }
    else {
      $this->messenger()->addStatus($this->t('The property has been add.'));
      $form_state->setRedirect('entity.myproperty.collection');
    }
    return $status;
  }
}

