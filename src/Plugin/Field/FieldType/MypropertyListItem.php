<?php

namespace Drupal\myproperty\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityType;


/**
 * @FieldType(
 *   id = "myproperty_list",
 *   label = @Translation("My property list field"),
 *   default_widget = "myproperty_list_default_widget",
 *   default_formatter = "myproperty_list_default_formatter"
 * )
 */
class MypropertyListItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'entity_type_id' => '',
      'bundle' => '',
      'is_label' => TRUE,
      'is_field_suffix' => TRUE,
    ] + parent::defaultStorageSettings();
  }


  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'field_types' => [],
    ] + parent::defaultFieldSettings();
  }


  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['label'] = DataDefinition::create('string')
      ->setLabel(t('Label'));
    $properties['label_colon'] = DataDefinition::create('boolean')
      ->setLabel(t('Label colon'));
    $properties['property_id'] = DataDefinition::create('integer')
      ->setLabel(t('Property id'))
      ->setRequired(TRUE);
    $properties['field_suffix'] = DataDefinition::create('string')
      ->setLabel(t('Field suffix'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema['columns']['property_id'] = [
      'description' => 'The property_id.',
      'type' => 'int',
      'unsigned' => TRUE,
    ];
    if ($field_definition->getSetting('is_label')) {
      $schema['columns']['label'] = [
        'description' => 'The label.',
        'type' => 'varchar',
        'length' => 255,
      ];
      $schema['columns']['label_colon'] = [
        'type' => 'int',
        'size' => 'tiny',
      ];
    }
    else {
      unset($schema['columns']['label']);
      unset($schema['columns']['label_colon']);
    }
    if ($field_definition->getSetting('is_field_suffix')) {
      $schema['columns']['field_suffix'] = [
        'description' => 'The field_suffix.',
        'type' => 'varchar',
        'length' => 255,
      ];
    }
    else {
      unset($schema['columns']['is_field_suffix']);
    }
    $schema['indexes'] = [
      'property_id' => [
        ['property_id']
      ],
    ];
    return $schema;
  }


  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->getValue();
    if (!isset($value['property_id']) || $value['property_id'] === '') {
      return TRUE;
    }
    return FALSE;
  }


  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];
    $settings = $this->getSettings();

    if (!empty($form_state->getUserInput()['settings']['entity_type_id'])) {
      $default_entity_type_id = $form_state->getUserInput()['settings']['entity_type_id'];
    }
    else {
      $default_entity_type_id = $settings['entity_type_id'];
    }



    $entity_types = \Drupal::entityTypeManager()->getDefinitions();
    //dump($entity_types);
    $options_entity_types = [];
    /** @var \Drupal\Core\Config\Entity\ConfigEntityType $entity_type */
    foreach ($entity_types as $entity_type) {
      if ($entity_type instanceof ContentEntityType) {
        $options_entity_types[$entity_type->id()] = $entity_type->getLabel();
      }
    }
    $element['entity_type_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Entity type id'),
      '#options' => $options_entity_types,
      '#default_value' => $default_entity_type_id,
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [get_class($this), 'ajaxRefresh'],
        'wrapper' => 'field-settings-bundle-wrapper',
      ],
      '#description' => 'Тип сушности, откуда брать поля',
      '#disabled' => $has_data,
    ];
    /** @var  \Drupal\Core\Entity\EntityManager */

    $bundles = \Drupal::entityManager()->getBundleInfo($default_entity_type_id);
    $options_bundles = [];
    foreach ($bundles as $bundle_id => $bundle) {
      $options_bundles[$bundle_id] = $bundle['label'];
    }
    $element['bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Bundle'),
      '#options' => $options_bundles,
      '#default_value' => !empty($options_bundles[$settings['bundle']]) ? $settings['bundle'] : '',
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
      '#prefix' => '<div id="field-settings-bundle-wrapper">',
      '#suffix' => '</div>',
      '#description' => 'Бандл типа сушности, откуда брать поля',
      '#disabled' => $has_data,
    ];
    $element['is_label'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use label'),
      '#default_value' => $settings['is_label'],
      '#description' => 'Использовать или нет колонку лейбл для полей',
      '#disabled' => $has_data,
    ];
    $element['is_field_suffix'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use field suffix'),
      '#default_value' => $settings['is_field_suffix'],
      '#description' => 'Использовать или нет колонку суфикса для полей',
      '#disabled' => $has_data,
    ];
    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $field_types = \Drupal::service('plugin.manager.field.field_type')
      ->getDefinitions();
    $options_field_types = [];
    foreach ($field_types as $field_type_id => $field_type) {
      $options_field_types[$field_type_id] = $field_type['label'];
    }
    $element['field_types'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#options' => $options_field_types,
      '#size' => min(15, count($options_field_types)),
      '#empty_option' => $this->t('- Select -'),
      '#title' => $this->t('Field types'),
      '#default_value' => $this->getSetting('field_types'),
      '#required' => TRUE,
    ];
    return $element;
  }


  public static function ajaxRefresh($form, FormStateInterface $form_state) {
    return $form['settings']['bundle'];
  }

}