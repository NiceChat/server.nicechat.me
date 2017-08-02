<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 日志类
*/
class LogLeiLei {

  public function write_log($str) 
  {
    $file = 'run.log';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= $str."\n";
    // Write the contents back to the file
    file_put_contents($file, $current);
  }
}