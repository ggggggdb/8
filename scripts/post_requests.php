<?php
function dropcookie($name){
    setcookie($name,'',100000);
}
function checkErrors_post($regex,$var,$value,$error){
  if(!preg_match($regex,$value) or empty($value)){
    dropcookie($var.'_value');
    setcookie($var.'_error', '1', time() + 24*60 * 60);
    $error=TRUE;
  }
  else{
    setcookie($var.'_value', $value, time() + 60 * 60);
    dropcookie($var.'_error');
  }
}
function cookiePowers($regex,$array_def,$power,&$a){
  if(preg_match($regex,$power)){
    setcookie(array_search($power,$array_def).'_value',1,time()+60*60);
    $a[array_search($power,$array_def)]=1;
  }
  else{

  }
}
function insert_powers_DB($powers,$table_name,$col,$id){
  foreach($powers as $power){
    db_insert($table_name,$col,'id',$id,$power);
  }
}