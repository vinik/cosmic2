<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');


class HomeController extends AppController {

  function index(){

  }

  function latest() {


    $file = new File(ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . 'sensors.log');
    $contents = $file->read();
    $file->close();

    $lines = explode(PHP_EOL, $contents);
    $lines = array_reverse($lines);

    $i=0;
    foreach ($lines as $line) {

      if ($line) {

        echo "<tr><td>" . $line . "</td></tr>";
      }

      $i++;
      if ($i >= 200) {
        break;
      }
    }

    die();

  }

}
