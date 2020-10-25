<?php

/**
* Login.class[ MODEL ]
* Responsavel por checar usuario e logar.
* @copyright (c) 2020, Alessandro da Rocha Filho
*/
class Login {
  private $Email;
  private $Senha;
  private $Error;
  private $Result;

  public function ExeLogin(array $UserData) {
    $this->Email = (string) strip_tags(trim($UserData['mem_email']));
    $this->Senha = (string) md5(trim($UserData['mem_password']));
    $this->setLogin();
  }

  function getResult() {
    return $this->Result;
  }

  function getError() {
    return $this->Error;
  }

  public function CheckLogin() {
    if(empty($_SESSION['userlogin-member'])):
      unset($_SESSION['userlogin-member']);
      return false;
    else:
      return true;
    endif;
  }

  //////////////////////////////////////////////
  ///////////////////PRIVATE////////////////////
  //////////////////////////////////////////////

  private function setLogin() {
    if (!$this->Email || !$this->Senha) {
      $this->Error = 'Informe seu email e senha para efetuar o login';
      $this->Result = false;
    }elseif (!$this->getUser()) {
      $this->Error = 'Os dados informados não são compativeis';
      $this->Result = false;
    }else {
      $this->Execute();
    }
  }

  private function getUser() {
    $Read = new Read;
    $Update = new Update;
    $Create = new Create;


    $Read->ExeRead("members", "WHERE mem_email = :e AND mem_password = :p", "e={$this->Email}&p={$this->Senha}");

    if ($Read->getResult()):
      $this->Result = $Read->getResult()[0];
      return true;
    else:
      return false;
    endif;
  }

  private function Execute() {
    if (!session_id()):
      session_start();
    endif;

    $_SESSION['userlogin-member'] = $this->Result;
    $this->Error = "Olá {$this->Result['mem_name']}, seja bem vindo. Aguade Redirecionamento!";
    $this->Result = true;
  }
}
