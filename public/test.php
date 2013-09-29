<?php

//$ARR = array('test' => 'OTO TEST');
//
//extract($ARR);
//include 'include.php';

//$VARS = [];
//$key = "cos.tam.";
//$key = str_replace('.', ".", $key);
//preg_match("/^($key)(.+)$/", "cos.tam.gdzies.tak", $VARS);
//
//var_dump($VARS);

//class Foo {
//  public function zrob()
//  {
//    $this->pisz();
//  }
//
//  private function pisz()
//  {
//    echo 'Rodzic';
//  }
//}
//
//class Bar extends Foo {
//  public function pisz()
//  {
//    echo 'Dziecko';
//  }
//}
//
//$a = new Bar();
//$a->zrob();

//if (is_array($Cos)) { echo 'ok'; }

//session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
//session_id('r38c2h2d23ntqefah6j1t44d75');

//$_SESSION['test'] = 'test';

var_dump(session_id(), md5('a'));