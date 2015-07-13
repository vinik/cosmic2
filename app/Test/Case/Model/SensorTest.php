<?php

App::uses('BaseCakeTestCase', 'Lib');

class SensorTest extends BaseCakeTestCase {

	public $uses = array('Sensor');

  public function testOcupar() {
    $data['par1:sensorIdentification']  = '123';
    $data['par1:status']                = 1;
    $data['par1:lastStatusDate']        = '2015-05-21T12:43:00';

    $resp = $this->Sensor->query('SaveParkingSpace', array($data));

    $this->assertTrue($resp);
  }

  public function testDesocupar() {
    $data['par1:sensorIdentification']  = '123';
    $data['par1:status']                = 1;
    $data['par1:lastStatusDate']        = '2015-05-21T12:43:00';

    $resp = $this->Sensor->query('SaveParkingSpace', array($data));

    $this->assertTrue($resp);
  }

}
