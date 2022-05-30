<?php
function login_get($request){
  session_start();
  if (!empty($_SESSION['login'])) {
    return redirect(' ');
  }
  else{
    return theme('login');
  }
}

function login_post(){
    $l=$_POST['login'];
    $p=$_POST['pass'];
    $uid=0;
    $error=TRUE;
    require_once('db.php');
    if(!empty($l) and !empty($p)){
        try{
            $pass=db_get('username','pass','login',$l);
print $pass;
            if(password_verify($p,$pass)){
                $uid=db_get('username','id','login',$l);
                $error=FALSE;
            }
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
    }
    if($error==TRUE){
        return theme('incorrect_log_in');
    }
    session_start();
    // Если все ок, то авторизуем пользователя.
    $_SESSION['login'] = $l;
    // Записываем ID пользователя.
    $_SESSION['uid'] = $uid;
    // Делаем перенаправление.
    return redirect(' ');
}
