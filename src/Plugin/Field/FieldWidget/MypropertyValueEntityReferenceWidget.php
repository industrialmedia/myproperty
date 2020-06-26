<?php


namespace Drupal\myproperty\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\myproperty\MypropertyStorageInterface;
use Drupal\myproperty\MypropertyValueStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


/**
 * @FieldWidget(
 *   id = "myproperty_value_entity_reference_widget",
 *   module = "myproperty",
 *   label = @Translation("Myproperty value entity reference widget"),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE
 * )
 */
class MypropertyValueEntityReferenceWidget extends WidgetBase implements ContainerFactoryPluginInterface {


  /**
   * The myproperty storage.
   *
   * @var \Drupal\myproperty\MypropertyStorageInterface.
   */
  protected $mypropertyStorage;


  /**
   * The myproperty_value storage.
   *
   * @var \Drupal\myproperty\MypropertyValueStorageInterface.
   */
  protected $mypropertyValueStorage;


  /**
   * Constructs a new ModerationStateWidget object.
   *
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Field definition.
   * @param array $settings
   *   Field settings.
   * @param array $third_party_settings
   *   Third party settings.
   * @param \Drupal\myproperty\MypropertyStorageInterface $myproperty_storage
   *   The myproperty storage.
   * @param \Drupal\myproperty\MypropertyValueStorageInterface $myproperty_value_storage
   *   The myproperty_value storage.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, MypropertyStorageInterface $myproperty_storage, MypropertyValueStorageInterface $myproperty_value_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->mypropertyStorage = $myproperty_storage;
    $this->mypropertyValueStorage = $myproperty_value_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')->getStorage('myproperty'),
      $container->get('entity_type.manager')->getStorage('myproperty_value')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\link\LinkItemInterface $item */
    /** @var \Drupal\Field\Entity\FieldConfig $field */
    /** @var \Drupal\myproperty\Entity\Myproperty $myproperty */
    /** @var \Drupal\myproperty\Entity\MypropertyValue $myproperty_value */

    $default_value = [];
    $items_values = $items->getValue();
    if (!empty($items_values)) {
      foreach ($items_values as $items_value) {
        if (!empty($items_value['target_id'])) {
          $default_value[] = $items_value['target_id'];
        }
      }
    }

    $myproperty_values = $this->mypropertyValueStorage->loadMultiple();
    $options = [];
    foreach ($myproperty_values as $myproperty_value) {
      $myproperty = $myproperty_value->getMyproperty();
      if ($myproperty) {
        $options[$myproperty->id()]['name'] = $myproperty->label();
        $options[$myproperty->id()]['values'][$myproperty_value->id()] = $myproperty_value->label();
      }

    }

    $element['target_id'] = [];
    if ($options) {
      foreach ($options as $property_id => $value) {
        $element['target_id'][$property_id] = [
          '#type' => 'select',
          '#title' => $value['name'],
          '#options' => $value['values'],
          '#empty_option' => '- Select -',
          '#default_value' => $default_value,
          '#required' => $element['#required'],
        ];
      }
    }

    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $values = array_diff($values['target_id'], array(''));
    return $values;
  }


  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getFieldStorageDefinition()
      ->getSetting('target_type') == 'myproperty_value';
  }


}
