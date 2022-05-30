<?php

// Обработчик запросов методом GET.
function front_get($request) {
  include('get_requests.php');
  session_start();
  $error_keys=array('name','email','year','sex','limb','power','check');
  $value_keys=array('name','email','year','sex','limb','immortal','ghost','levitation','bio','check');
  $power_val=array(
    'immortal'=>'бессмертие',
    'ghost'=>'прохождение сквозь стены',
    'levitation'=>'левитация'
  );

  $messages = array();
  $errors = array();
  $values = array();
  $error=FALSE;

  if (!empty($_COOKIE['save'])) {
    dropcookie('save');
    dropcookie('login');
    dropcookie('pass_in');
    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['pass_in'])) {
      $messages[] = sprintf('Вы можете <a href="login">войти</a> с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.',strip_tags($_COOKIE['login']),strip_tags($_COOKIE['pass_in']));
    }
    foreach($_COOKIE as $key=>$value){
      dropcookie($key);
    }
  }
  
  foreach($error_keys as $key){
    $errors[$key]=!empty($_COOKIE[$key.'_error']);
  }
  foreach($errors as $key=>$value){
    checkErrors_get($key,$value,$messages,$error);
  }
  
  foreach($value_keys as $key){
    $values[$key]=empty($_COOKIE[$key.'_value']) ? '' : strip_tags($_COOKIE[$key.'_value']);
  }
  if (!$error and !empty($_COOKIE[session_name()]) and !empty($_SESSION['login'])) {
    try{
      require('db.php');
      $app=db_query('SELECT * FROM application where id=?',$_SESSION['uid']);
      foreach(array_shift($app) as $k=>&$v){
        $values[$k]=$v;
      }
      $pwrs=db_query('SELECT power FROM powers where id=?',$_SESSION['uid']);
      foreach($pwrs as $pwr){
        if(in_array($pwr['power'],$power_val))
          $values[array_search($pwr['power'],$power_val)]=1;
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    $messages['loggedin']='Вход с логином '.$_SESSION['login'].', uid '.$_SESSION['uid'];
  }
  $params=array(
    'values'=>$values,
    'errors'=>$errors,
    'messages'=>$messages
  );
  return theme('page',['front'=>$params]);
  // Пример ответа веб-сервиса.
  return array('headers' => array('Content-Type' => '/xml'), 'entity' => '<document />');
  // Пример возврата контента.
  return '123';
  // Пример запрета доступа.
  return access_denied();
  // Пример возврата ресурс не найден.
  return not_found();
}

// Обработчик запросов методом POST.
function front_post($request) {
  session_start();
  if(!empty($_POST['logout'])){
    session_destroy();
    redirect('');
  }
  else{
    require('post_requests.php');
    $db_keys=array('name','email','year','sex','limb','bio','power');
    $user_keys=array('login','pass');
    //регулярки для проверки
    $regex_def=array(
      'name'=>'/[a-z,A-Z,а-я,А-Я,-]*$/',
      'email'=>'/[a-z]+\w*@[a-z]+\.[a-z]{2,4}$/',
      'year'=>'/[12][089][0-9]{2}$/',
      'sex'=>'/[MW]$/',
      'limb'=>'/[1-4]$/',
      'check'=>'/on$/',
      'power'=>'/бессмертие|прохождение сквозь стены|левитация$/'
    );
    //массив для проверки сил
    $powers_def=array(
      'immortal'=>'бессмертие',
      'ghost'=>'прохождение сквозь стены',
      'levitation'=>'левитация'
    );
    $errors = FALSE;
    //проверка ошибок в отправленных данных (кроме сил)
    foreach($regex_def as $key_name=>$regex){
      if(!empty($_SESSION['login']) and $key_name=='check' or $key_name=='power'){
        break;
      }
      checkErrors_post($regex,$key_name,$_POST[$key_name],$errors);
    }
    //проверка сил
    if (!isset($_POST['power'])) {
      setcookie('powers_error', '1', time() + 24 * 60 * 60);
      foreach($powers_def as $pwr){
        dropcookie($pwr.'_value');
      }
      $errors = TRUE;
    }
    else {
      //запись куки для сил
      $powers_bool=array('immortal'=>0,'ghost'=>0,'levitation'=>0);
      foreach($_POST['power'] as $pwr){
        cookiePowers($regex_def['power'],$powers_def,$pwr,$powers_bool);
      }
      //очистка старых куки
      foreach($powers_bool as $c=>$val){
        if($val==0){
          setcookie($c,'',100000);
        }
      }
    }

    //запись куки для биографии
    setcookie('bio_value',$_POST['bio'],time()+ 60*60);

    if ($errors) {
      setcookie('save','',100000);
      redirect('');
    }
    else {
      foreach($regex_def as $key=>$v){
        dropcookie($key.'_error');
      }
    }

    //работа с бд
    require('db.php');
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
      $id=$_SESSION['uid'];
      foreach($db_keys as $key){
        if($key!='power'){
          db_set('application',$key,'id',$id,$_POST[$key]);
        }
        else{
          db_rmAll('powers',$key,'id',$id);
          insert_powers_DB($_POST[$key],'powers','power',$id);
        }
      }
    }
    else {
      if(!$errors){
        $login = 'u'.substr(uniqid(),-5);
        $pass = substr(md5(uniqid()),10,10);
        $pass_hash=password_hash($pass,PASSWORD_DEFAULT);
        setcookie('login', $login);
        setcookie('pass_in', $pass);
        try {
          $id=db_get_last('application','id','id')+1;
          $user=array(
            'id'=>$id,
            'login'=>$login,
            'pass'=>$pass_hash
          );
          foreach($db_keys as $key){
            if($key!='power'){
              db_set('application',$key,'id',$id,$_POST[$key]);
            }
            else{
              insert_powers_DB($_POST[$key],'powers','power',$id);
            }
          }
          foreach($user_keys as $key){
            db_set('username',$key,'id',$id,$user[$key]);
          }
        }
        catch(PDOException $e){
          print('Error : ' . $e->getMessage());
          exit();
        }
      }
    }
    if(!$errors){
      setcookie('save', '1');
    }
  }
  return redirect('');
}
