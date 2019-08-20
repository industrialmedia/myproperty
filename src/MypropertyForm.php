<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\myproperty\Entity\Myproperty;

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
    /* @var  \Drupal\myproperty\Entity\Myproperty $myproperty */
    $myproperty = $this->entity;
    $form['machine_name'] = [
      '#type' => 'machine_name',
      '#default_value' => $myproperty->getMachineName(),
      // '#disabled' => !$myproperty->isNew(), // Позволяем его менять
      '#maxlength' => 64,
      '#description' => 'Уникальное имя, изменять это значение не рекомендуют, оно может использоваться в стилях, урле...',
      '#machine_name' => [
        'exists' => [$this, 'machineNameExists'],
        'source' => ['name', 'widget', 0, 'value'],
        'replace_pattern' => '[^a-z0-9-]+',
        'replace' => '-',
      ],
    ];
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


  /**
   * Determines if the myproperty already exists.
   *
   * @param string $machine_name
   *   The myproperty machine_name.
   *
   * @return bool
   *   TRUE if the myproperty exists, FALSE otherwise.
   */
  public function machineNameExists($machine_name) {
    $myproperty = Myproperty::loadByMachineName($machine_name);
    return !empty($myproperty);
  }
  
}

