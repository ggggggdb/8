<?php
if(!empty($_COOKIE[session_name()]) and (!empty($_SESSION['login']) or !empty($_SESSION['uid']))){
    session_destroy();
}
?>
Неправильные логин или пароль <br>
Если вы хотите создать нового пользователя <a href="">на главную</a> или попытайтесь войти снова <a href="login">войти</a>