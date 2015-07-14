<?php

App::uses('AppController', 'Controller');

class PswnController extends AppController {

  public $uses = array('Sensor');

  /**
   * Método que recolhe os dados enviados pelos sensores (REST) e envia para o ws (SOAP)
   */
  public function sheeps($sensores = null) {

    error_reporting(0);
    @ini_set('display_errors', 0);

    $this->layout = 'ajax';

    CakeLog::config('sensor', array(
      'engine' => 'FileLog',
      'types' => array('warning', 'error', 'info'),
      'scopes' => array('sensors'),
      'file' => 'sensors.log',
    ));

    //Envio de comandos aos sensores
    if ($this->request->is('get')) {

      $this->logMe('GET /pswn/sheeps/' . $sensores);

      $return = '';

      if (! is_null($sensores)) {
        //Sensores enviados
        $arrSensores = explode(',', $sensores);

        //conteudo a ser respondido

        //entre os sensores pedidos
        foreach ($arrSensores as $indexSensor => $itemSensor) {


          //se existe algum comando a ser enviado para este sensor
          if (file_exists(WWW_ROOT. 'files' . DS . $itemSensor)) {

            $this->logMe('Enviando comando ao sensor '. $itemSensor);

            if ($return != '') {
              $return .= ',';
            }

            $file = new File(WWW_ROOT. 'files' . DS . $itemSensor);
            $contents = $file->read();
            $file->close();

            //copia o conteudo do arquivo para a resposta
            $return .= $contents;
            $return = str_replace('\n', '', $return);


          } else {
            $this->logMe('Nada a ser enviado para o sensor ' . $itemSensor);
          }
        }
      }

      $jsonResponse = '{"sheeps":[' . $return . ']}';
      $this->set('jsonResponse', $jsonResponse);
    }

    //Os shepherds enviam requests PUT
    if ($this->request->is('put')) {

      $this->logMe('PUT /pswn/sheeps/');

      //dados enviado no request
      $jsonInput = $this->request->input();
      $json = json_decode($jsonInput);

      //data/hora da mensagem
      $date = $json->data;
      $d1 = explode('+', $date);//desconsiderando a última parte

      //construção da mensagem a ser enviada para o ws soap
      $data = array(
        'userIdentification'  => 'sensores',
        'parkingspace'        => array()
      );

      //Comandos processados
      if (isset($json->processados)) {
        foreach ($json->processados as $processadoItem) {

          $this->logMe('Processado: ' . $processadoItem->uin);

          //apaga o arquivo
          if (file_exists(WWW_ROOT. 'files' . DS . $processadoItem->uin)) {
            unlink(WWW_ROOT. 'files' . DS . $processadoItem->uin);
          }
        }

      }

      $total = 0;

      if (isset($json->sensores)) {

        $total = count($json->sensores);

        //passando por todos os sensores enviados
        foreach ($json->sensores as $sensorItem) {

          $logMsg = '';

          $status = $sensorItem->vaga_ocupada == 1 ? 1 : 2;

          $item = array(
            'sensorIdentification'   => $sensorItem->uin,
            'status'                 => $status,
            'lastStatusDate'         => $d1[0]
          );

          $logMsg .= $d1[0] . ': ' . $sensorItem->uin . ' - ' . $status;
          $this->logMe($logMsg);

          array_push($data['parkingspace'], $item);

        }

        //envio da mensagem
        try {
          $resp = $this->Sensor->query('SaveParkingSpaceOp', array($data));
        } catch (Exception $e) {
          $resp = false;
        }
        //echo "<PRE>";
        //die(var_dump($resp));

        if ($resp === false) {
          $this->logMe('Não foi possível enviar para o webservice');
        } else {
          $this->logMe('Enviado para o webservice');

          foreach ($resp->resultItem as $resultItem) {

            if (isset($resultItem->identification)) {
              $resultMsg = 'Id vaga: ' ;
              $resultMsg .= $resultItem->identification;

            } else {
              $resultMsg = $resultItem->faultList->faultItem->code . ' (' . $resultItem->faultList->faultItem->date . '): ' . $resultItem->faultList->faultItem->description;
            }

            $this->logMe($resultMsg);
          }

        }
      }

      //resposta para a requisição dos sensores
      $jsonResponse = '{"created":0,"updated":' . $total . ',"rejected":0,"checked":0,"errors_at":[]}';
      $this->set('jsonResponse', $jsonResponse);
    }

    $this->response->type('json');

  }

  /*
   *
   */
  public function shepherds() {
    CakeLog::config('sensor', array(
      'engine' => 'FileLog',
      'types' => array('warning', 'error', 'info'),
      'scopes' => array('sensors'),
      'file' => 'sensors.log',
    ));

    $this->layout = 'ajax';

    if ($this->request->is('put')) {
      $this->logMe('PUT /pswn/shepherds');

      $return = '{"action":"updated"}';

    } else if ($this->request->is('get')) {
      $this->logMe('GET /pswn/shepherds');
      $str = '';

      $return = '{"timeslice":"1/6","beacon_time":23,"bcc":120}';
    }

    $this->response->type('json');
    $this->set('jsonResponse', $return);
  }

  private function logMe($msg) {
    CakeLog::info($msg, 'sensors');
  }



}
