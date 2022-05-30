<style>
  .form-sign-in{
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
  }
  input{
    margin-bottom: 3px;
  }
</style>
<div class="form-sign-in">
<form action="login" method="post">
  <label> Логин <label> <br>
  <input name="login" /> <br>
  <label> Пароль <label> <br>
  <input name="pass" type="password"/> <br>
  <input type="submit" value="Войти" />
</form>
</div>