<?php

// Обработчик запросов методом GET.
function admin_get($request) {
  require_once('db.php');
  if(empty($request[1])){ 
    $query=db_array(1,"SELECT * FROM application");
    $params = array();
    for($i=0;$i<count($query);$i++){
      $params[$i]=$query[$i];
    }
    
    $query=db_array(1,"SELECT * FROM powers");
    for($i=0;$i<count($params);$i++){
      $m=0;
      for($j=0;$j<count($query);$j++)
        if($params[$i]['id']==$query[$j]['id']){
          $params[$i]['powers'][$m]=$query[$j]['power'];
          $m++;
        }
    }
    // Пример возврата html из шаблона с передачей параметров.
    return theme('admin', ['admin' => $params]);
  }
  else{
    include('get_requests.php');
    $error_keys=array('name','email','year','sex','limb','power');
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
      $messages[] = 'Спасибо, результаты сохранены.';
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
    try{
      $app=db_query('SELECT * FROM application where id=?',$request[0]);
      foreach(array_shift($app) as $k=>&$v){
        $values[$k]=$v;
      }
      $pwrs=db_query('SELECT power FROM powers where id=?',$request[0]);
      foreach($pwrs as $pwr){
        if(in_array($pwr['power'],$power_val))
          $values[array_search($pwr['power'],$power_val)]=1;
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    $params=array(
      'id'=>$request[0],
      'values'=>$values,
      'errors'=>$errors,
      'messages'=>$messages
    );
    return theme('edit',['edit'=>$params]);
  }
}

// Обработчик запросов методом POST.
function admin_post($request) {
  require_once('db.php');
  $id = intval($request[0]);
  switch($_POST['action']){
    case 'Delete': 
    case 'Удалить':
      db_rmAll('powers','*','id',$id);
      db_rmAll('application','*','id',$id);
      db_rmAll('username','*','id',$id);
      return redirect('admin');
    case 'Изменить':
      return redirect('admin/'.$id.'/edit');
    case 'Назад':
      return redirect('admin');
    case 'Edit':
      require('post_requests.php');
      $db_keys=array('name','email','year','sex','limb','bio','power');
      //регулярки для проверки
      $regex_def=array(
        'name'=>'/[a-z,A-Z,а-я,А-Я,-]*$/',
        'email'=>'/[a-z]+\w*@[a-z]+\.[a-z]{2,4}$/',
        'year'=>'/[12][089][0-9]{2}$/',
        'sex'=>'/[MW]$/',
        'limb'=>'/[1-4]$/',
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
        if($key_name=='power'){
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
      foreach($db_keys as $key){
        if($key!='power'){
          db_set('application',$key,'id',$id,$_POST[$key]);
        }
        else{
          db_rmAll('powers',$key,'id',$id);
          insert_powers_DB($_POST[$key],'powers','power',$id);
        }
      }
      if(!$errors){
        setcookie('save', '1');
      }
      return redirect('admin/'.$id.'/edit');
  }
  
  // Пример возврата редиректа после обработки формы для реализации принципа Post-redirect-Get.
  
}
