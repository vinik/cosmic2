<?php

App::uses('AppModel', 'Model');

class Sensor extends AppModel {

  public $name = 'Sensor';

  var $useTable = false;

  var $useDbConfig = 'soap';

}
