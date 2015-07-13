<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class PswnControllerTest extends IntegrationTestCase
{

    public function testIndex()
    {
        $this->get('/pswn');

        $this->assertResponseOk();
        // More asserts.
    }

    public function testOcupar() {
      $reqJson = '{
        "imei":"0",
        "mac":"00:06:66:67:f5:00",
        "data":"2015-06-10T13:27:13+00",
        "sensores":[
          {
            "uin":"0000001F002D700526934E45",
            "epc":"AAAAAAAAAAAAAAAAAAAAAAAA",
            "i_carga_ua":590,
            "i_descarga_ua":1399,
            "v_bat1_v":"4.101",
            "v_bat2_v":"4.226",
            "v_cel_v":"5.438",
            "t_placa_c":23,
            "vaga_ocupada":1,
            "jamming":1,
            "vibracao":0,
            "superaquecimento":0,
            "lowbat":0,
            "bcc":255
          },
          {
            "uin":"000000290064700526934E45",
            "epc":"000000000000000000000000",
            "i_carga_ua":30387,
            "i_descarga_ua":30905,
            "v_bat1_v":"3.930",
            "v_bat2_v":"4.076",
            "v_cel_v":"5.145",
            "t_placa_c":26,
            "vaga_ocupada":0,
            "jamming":0,
            "vibracao":0,
            "superaquecimento":0,
            "lowbat":0,
            "bcc":233
          },
          {
            "uin":"000000570053700526934E45",
            "epc":"000000000000000000000000",
            "i_carga_ua":32792,
            "i_descarga_ua":34114,
            "v_bat1_v":"4.055",
            "v_bat2_v":"4.039",
            "v_cel_v":"5.164",
            "t_placa_c":25,
            "vaga_ocupada":0,
            "jamming":0,
            "vibracao":0,
            "superaquecimento":0,
            "lowbat":0,
            "bcc":228
          }
        ]
      }';

      $data = array($reqJson);

      // Configure headers
      $this->configRequest([
        'headers' => ['Accept' => 'application/json']
      ]);

      $this->put('/pswn', $data);

      $this->assertResponseOk();

    }

    public function testDesocupar() {

    }

}
