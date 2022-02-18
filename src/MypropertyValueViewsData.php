<?php

namespace Drupal\myproperty;

use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the MypropertyValue entity type.
 */
class MypropertyValueViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();


    $data['myproperty_value_field_data']['table']['base']['help'] = $this->t('MypropertyValue entity.');
    $data['myproperty_value_field_data']['table']['wizard_id'] = 'myproperty_value';



    $data['myproperty_value_field_data']['id']['help'] = $this->t('The id of a MypropertyValue.');

    $data['myproperty_value_field_data']['id']['filter']['id'] = 'myproperty_value_id';
    $data['myproperty_value_field_data']['id']['filter']['title'] = $this->t('MypropertyValue');
    $data['myproperty_value_field_data']['id']['filter']['help'] = $this->t('MypropertyValue chosen from autocomplete or select widget.');
    $data['myproperty_value_field_data']['id']['filter']['numeric'] = TRUE;

    $data['myproperty_value_field_data']['id_raw'] = [
      'title' => $this->t('MypropertyValue ID'),
      'help' => $this->t('The id of a MypropertyValue.'),
      'real field' => 'id',
      'filter' => [
        'id' => 'numeric',
        'allow empty' => TRUE,
      ],
    ];
  





    return $data;
  }

}
