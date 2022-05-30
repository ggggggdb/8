<?php
function checkErrors_get($index,$index_value,$messages,&$error){
  $errors_def=array(
    'name'=> '<div class="error">Заполните имя.</div>',
    'email'=> '<div class="error">Заполните или исправьте почту.</div>',
    'year'=> '<div class="error">Выберите год рождения.</div>',
    'sex'=> '<div class="error">Выберите пол.</div>',
    'limb'=> '<div class="error">Выберите сколько у вас конечностей.</div>',
    'power'=> '<div class="error">Выберите хотя бы одну суперспособность.</div>',
    'check'=> '<div class="error">Необходимо согласиться с политикой конфиденциальности.</div>'
  );
  if($index_value){
    dropcookie($index.'_error');
    if(array_key_exists($index,$errors_def))
      $messages[]=$errors_def[$index];
    $error=TRUE;
  }
}
function dropcookie($name){
    setcookie($name,'',100000);
}