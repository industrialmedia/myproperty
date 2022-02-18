<?php

namespace Drupal\myproperty\Plugin\views\filter;


use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\ManyToOne;
use Drupal\myproperty\Entity\MypropertyValue;

/**
 * Filter by MypropertyValue id.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("myproperty_value_id")
 */
class MypropertyValueId extends ManyToOne {

  /**
   * Stores the exposed input for this filter.
   *
   * @var array|null
   */
  public $validated_exposed_input = NULL;


  public function hasExtraOptions() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getValueOptions() {
    return $this->valueOptions;
  }


  protected function valueForm(&$form, FormStateInterface $form_state) {

    $options = [];
    $query = \Drupal::entityQuery('myproperty_value')
      ->sort('weight')
      ->sort('name');
    $myproperty_values = MypropertyValue::loadMultiple($query->execute());
    foreach ($myproperty_values as $myproperty_value) {
      $options[$myproperty_value->id()] = \Drupal::service('entity.repository')
        ->getTranslationFromContext($myproperty_value)
        ->label();
    }

    $default_value = (array) $this->value;
    if ($exposed = $form_state->get('exposed')) {
      $identifier = $this->options['expose']['identifier'];
      if (!empty($this->options['expose']['reduce'])) { // Ограничить список выбранными элементами, Если отмечено, пользователю будут доступны только выбранные здесь варианты.
        $options = $this->reduceValueOptions($options);
        if (!empty($this->options['expose']['multiple']) && empty($this->options['expose']['required'])) {
          $default_value = [];
        }
      }
      if (empty($this->options['expose']['multiple'])) {
        if (empty($this->options['expose']['required']) && (empty($default_value) || !empty($this->options['expose']['reduce']))) {
          $default_value = 'All';
        }
        elseif (empty($default_value)) {
          $keys = array_keys($options);
          $default_value = array_shift($keys);
        }
        elseif ($default_value == ['']) {
          $default_value = 'All';
        }
        else {
          $copy = $default_value;
          $default_value = array_shift($copy);
        }
      }
    }

    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Property_values'),
      '#multiple' => TRUE,
      '#options' => $options,
      '#size' => min(9, count($options)),
      '#default_value' => $default_value,
    ];

    $user_input = $form_state->getUserInput();
    if ($exposed && isset($identifier) && !isset($user_input[$identifier])) {
      $user_input[$identifier] = $default_value;
      $form_state->setUserInput($user_input);
    }

    if (!$form_state->get('exposed')) {
      $this->helper->buildOptionsForm($form, $form_state);
      $form['value']['#description'] = t('Leave blank for all. Otherwise, the first selected myproperty_value will be the default instead of "Any".');
    }

  }


  public function acceptExposedInput($input) {

    if (empty($this->options['exposed'])) {
      return TRUE;
    }

    if (!empty($this->options['expose']['use_operator']) && !empty($this->options['expose']['operator_id']) && isset($input[$this->options['expose']['operator_id']])) {
      $this->operator = $input[$this->options['expose']['operator_id']];
    }

    if (!empty($this->view->is_attachment) && $this->view->display_handler->usesExposed()) {
      $this->validated_exposed_input = (array) $this->view->exposed_raw_input[$this->options['expose']['identifier']];
    }

    if ($this->operator == 'empty' || $this->operator == 'not empty') {
      return TRUE;
    }

    if (!$this->options['expose']['required'] && empty($this->validated_exposed_input)) {
      return FALSE;
    }

    $rc = parent::acceptExposedInput($input);
    if ($rc) {
      if (isset($this->validated_exposed_input)) {
        $this->value = $this->validated_exposed_input;
      }
    }

    return $rc;
  }

  public function validateExposed(&$form, FormStateInterface $form_state) {
    if (empty($this->options['exposed'])) {
      return;
    }
    $identifier = $this->options['expose']['identifier'];
    if ($form_state->getValue($identifier) != 'All') {
      $this->validated_exposed_input = (array) $form_state->getValue($identifier);
    }
  }



  public function adminSummary() {
    // set up $this->valueOptions for the parent summary
    $this->valueOptions = [];
    if ($this->value) {
      $this->value = array_filter($this->value);
      $myproperty_values = MypropertyValue::loadMultiple($this->value);
      foreach ($myproperty_values as $myproperty_value) {
        $this->valueOptions[$myproperty_value->id()] = \Drupal::service('entity.repository')
          ->getTranslationFromContext($myproperty_value)
          ->label();
      }
    }
    return parent::adminSummary();
  }




}
