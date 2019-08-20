<?php

namespace Drupal\myproperty;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Component\Datetime\TimeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\myproperty\Entity\MypropertyValue;



/**
 * Form controller for the myproperty entity edit forms.
 *
 * @ingroup myproperty
 */
class MypropertyValueForm extends ContentEntityForm {


  /**
   * The property_id.
   *
   * @var int
   */
  protected $property_id;


  /**
   * Constructs a ContentEntityForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL, TimeInterface $time = NULL) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->property_id = \Drupal::request()->attributes->get('myproperty');
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {

    /* @var \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository */
    $entity_repository = $container->get('entity.repository');
    
    return new static(
      $entity_repository,
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

    /* @var  \Drupal\myproperty\Entity\MypropertyValue $myproperty_value */
    $myproperty_value = $this->entity;
    $form['machine_name'] = [
      '#type' => 'machine_name',
      '#default_value' => $myproperty_value->getMachineName(),
      // '#disabled' => !$myproperty_value->isNew(), // Позволяем его менять
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
    /* @var  \Drupal\myproperty\Entity\MypropertyValue $myproperty_value */
    $myproperty_value = $this->entity;
    $myproperty_value_exists = MypropertyValue::loadByMachineNameAndPropertyId($machine_name, $myproperty_value->getPropertyId());
    return !empty($myproperty_value_exists);
  }



}

