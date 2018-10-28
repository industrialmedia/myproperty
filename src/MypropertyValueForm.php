<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Component\Datetime\TimeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the myproperty entity edit forms.
 *
 * @ingroup myproperty
 */
class MypropertyValueForm extends ContentEntityForm {

  protected $property_id;


  public function __construct(EntityManagerInterface $entity_manager, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL, TimeInterface $time = NULL) {
    parent::__construct($entity_manager, $entity_type_bundle_info, $time);
    $this->property_id = \Drupal::request()->attributes->get('myproperty');
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $callback_object \Drupal\mynotify\MynotifyForm */
    $callback_object = $form_state->getBuildInfo()['callback_object'];
    $operation = $callback_object->getOperation();
    if ($operation == 'add') {
      /* @var $myproperty_value \Drupal\myproperty\Entity\MypropertyValue */
      $myproperty_value = $this->getEntity();
      if (empty($myproperty_value->getPropertyId())) {
        $myproperty_value->setPropertyId($this->property_id);
      }
    }
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    /* @var $myproperty_value \Drupal\myproperty\Entity\MypropertyValue */
    $myproperty_value = $this->getEntity();
    if ($status == SAVED_UPDATED) {
      $this->messenger()
        ->addMessage($this->t('The property value has been updated.'));
      $form_state->setRedirect('entity.myproperty.list_property_values', ['myproperty' => $myproperty_value->getPropertyId()]);
    }
    else {
      $this->messenger()
        ->addMessage($this->t('The property value has been add.'));
      $form_state->setRedirect('entity.myproperty.list_property_values', ['myproperty' => $myproperty_value->getPropertyId()]);
    }
    return $status;
  }
}

