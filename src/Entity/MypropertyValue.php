<?php

namespace Drupal\myproperty\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\myproperty\MypropertyValueInterface;
use Drupal\user\UserInterface;



/**
 * Defines the MypropertyValue entity.
 *
 * @ingroup myproperty
 *
 * @ContentEntityType(
 *   id = "myproperty_value",
 *   label = @Translation("MypropertyValue entity"),
 *   handlers = {
 *     "storage" = "Drupal\myproperty\MypropertyValueStorage",
 *     "storage_schema" = "Drupal\myproperty\MypropertyValueStorageSchema",
 *     "list_builder" = "Drupal\myproperty\MypropertyValueListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\myproperty\MypropertyValueForm",
 *       "edit" = "Drupal\myproperty\MypropertyValueForm",
 *       "delete" = "Drupal\myproperty\Form\MypropertyValueDeleteForm",
 *     },
 *     "access" = "Drupal\myproperty\MypropertyValueAccessControlHandler",
 *   },
 *   fieldable = FALSE,
 *   translatable = TRUE,
 *   base_table = "myproperty_value",
 *   data_table = "myproperty_value_field_data",
 *   admin_permission = "administer myproperty_value entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "weight" = "weight",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/myproperty_value/{myproperty_value}/edit",
 *     "edit-form" = "/admin/content/myproperty_value/{myproperty_value}/edit",
 *     "add-form" = "/admin/content/myproperty/{myproperty}/add",
 *     "delete-form" = "/admin/content/myproperty/{myproperty_value}/delete",
 *     "collection" = "/admin/content/myproperty/{myproperty}"
 *   },
 *   field_ui_base_route = "entity.myproperty.list_property_values",
 * )
 *
 * The 'links':
 * entity.<entity-name>.<link-name>
 * Example: 'entity.myproperty_value.canonical'
 *
 *  *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 */
class MypropertyValue extends ContentEntityBase implements MypropertyValueInterface {




  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->get('weight')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWeight($weight) {
    $this->set('weight', $weight);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations() {
    $changed = $this->getUntranslated()->getChangedTime();
    /* @var \Drupal\Core\Language\Language $language */
    foreach ($this->getTranslationLanguages(FALSE) as $language) {
      $translation_changed = $this->getTranslation($language->getId())
        ->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }


  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getPropertyId() {
    return $this->get('property_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setPropertyId($property_id) {
    $this->set('property_id', $property_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMyproperty() {
    return $this->get('property_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setMyproperty($myproperty) {
    $this->set('property_id', $myproperty->id());
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getMachineName() {
    return $this->get('machine_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMachineName($machine_name) {
    $this->set('machine_name', $machine_name);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public static function loadByMachineName($machine_name) {
    $myproperty_values = \Drupal::entityTypeManager()
      ->getStorage('myproperty_value')
      ->loadByProperties(['machine_name' => $machine_name]);
    if (empty($myproperty_values)) {
      return NULL;
    }
    return reset($myproperty_values);
  }


  /**
   * {@inheritdoc}
   */
  public static function loadByMachineNameAndPropertyId($machine_name, $property_id) {
    $myproperty_values = \Drupal::entityTypeManager()
      ->getStorage('myproperty_value')
      ->loadByProperties(['machine_name' => $machine_name, 'property_id' => $property_id]);
    if (empty($myproperty_values)) {
      return NULL;
    }
    return reset($myproperty_values);
  }



  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Property name'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['machine_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Machine name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255);
    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'));
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));
    $fields['weight'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Weight'))
      ->setRequired(TRUE)
      ->setDefaultValue(0)
      ->setDisplayOptions('form', array(
        'type' => 'integer',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['property_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Property'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'myproperty')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);
    return $fields;
  }


  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'uid' => \Drupal::currentUser()->id(),
    );
  }


  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    $machine_name = $this->getMachineName();
    if (empty($machine_name)) {
      $name = $this->getName();
      $name_translit = _myproperty_transliterate($name);
      $machine_name = $name_translit;
      $i = 2;
      while ($myproperty_value = MypropertyValue::loadByMachineNameAndPropertyId($machine_name, $this->getPropertyId())) {
        $machine_name = $name_translit . '-' . $i;
        $i++;
      }
      $this->setMachineName($machine_name);
    }
  }







}