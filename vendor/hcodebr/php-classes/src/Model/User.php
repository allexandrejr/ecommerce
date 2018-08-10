<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

  const SESSION = "User";

  public static function login($login, $password)
  {

    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
      ":LOGIN"=>$login
    ));

    if (count($results) === 0)
    {
      throw new \Exception("Usuário inexistente ou senha iválida.", 1);
    }

    $data = $results[0];

    if (password_verify($password, $data["despassword"]) === true)
    {

      $user = new User();

      $user->setData($data);

      $_SESSION[User::SESSION] = $user->getValues();

      return $user;
      //var_dump($user);
      //exit;

    } else {
      throw new \Exception("Usuário inexistente ou senha iválida.", 1);
    }
  }

  public static function verifyLogin($inadmin = true)
  {

    //var_dump($_SESSION);
    //exit;

    if (
      !isset($_SESSION[User::SESSION])
      ||
      !$_SESSION[User::SESSION]
      ||
      !(int)$_SESSION[User::SESSION]["iduser"] > 0
      ||
      (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
      )
    {
      header("Location: /admin/login");
      exit;
    }

  }

}

 ?>
