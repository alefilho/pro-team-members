<?php
session_start();
require '../../_app/Config.inc.php';

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = explode('.', basename($_SERVER['PHP_SELF']))[0];

//VALIDA AÇÃO
if ($PostData && $PostData['AjaxFile'] == $File):
  //PREPARA OS DADOS
  $Case = $PostData['AjaxAction'];
  unset($PostData['AjaxAction'], $PostData['AjaxFile']);

  // AUTO INSTANCE OBJECT READ
  if (empty($Read)):
    $Read = new Read;
  endif;

  // AUTO INSTANCE OBJECT CREATE
  if (empty($Create)):
    $Create = new Create;
  endif;

  // AUTO INSTANCE OBJECT UPDATE
  if (empty($Update)):
    $Update = new Update;
  endif;

  // AUTO INSTANCE OBJECT DELETE
  if (empty($Delete)):
    $Delete = new Delete;
  endif;

  //SELECIONA AÇÃO
  switch ($Case):
    case 'ExeLogin':
      $login = new Login;
      $login->ExeLogin($PostData);

      if ($login->getResult()) {
        $jSON['location'] = BASE . '/panel.php';
        // $jSON['trigger'] = ToastError($login->getError());
      }else {
        $jSON['trigger'] = ToastError($login->getError(), E_USER_WARNING);
      }
      break;

    case 'recover':
      $PostData['mem_email'] = ((!empty($PostData['mem_email'])) ? strtolower($PostData['mem_email']) : null);

      if (!Check::Email($PostData['mem_email'])) {
        $jSON['trigger'] = ToastError("Email Inválido", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      $Read->FullRead("SELECT * FROM members WHERE mem_email = '{$PostData['mem_email']}'");
      if (!$Read->getResult()) {
        $jSON['trigger'] = ToastError("Email não cadastrado", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      $user = $Read->getResult()[0];

      $hash = md5($user['mem_id'] . date("Y-m-d H:i:s") . rand(0, 99999));

      unset($Up);
      $Up['mem_tokenrecovery'] = $hash;

      $Update->ExeUpdate("members", $Up, "WHERE mem_id = :id", "id={$user['mem_id']}");

      $body = "Olá <b>{$user['mem_name']}</b>, </br></br> Para redefinir a sua senha acesse o link abaixo:";
      $body .= "</br></br><a href='" . BASE . "/recover.php?token=" . $hash . "'> " . BASE . "/recover.php?token=" . $hash . "</a>";
      $body .= "</br></br> Atenciosamente,</br>Equipe " . TITLE;

      require_once '../../_app/Library/PHPMailer/PHPmailer.php';
      require_once '../../_app/Library/PHPMailer/SMTP.php';

      $Mailer = new PHPMailer();
      $Mailer->IsSMTP();
      $Mailer->CharSet = 'UTF-8';
      $Mailer->SMTPDebug = 0;
      $Mailer->Port = MAIL_PORT; //Indica a porta de conexão
      $Mailer->Host = MAIL_HOST;//Endereço do Host do SMTP
      $Mailer->SMTPAuth = true; //define se haverá ou não autenticação
      $Mailer->Username = MAIL_USER; //Login de autenticação do SMTP
      $Mailer->Password = MAIL_PASS; //Senha de autenticação do SMTP
      $Mailer->FromName = TITLE; //Nome que será exibido
      $Mailer->From = MAIL_FROM; //Obrigatório ser a mesma caixa
      $Mailer->IsHTML(true);
      $Mailer->AddAddress($user['mem_email'], $user['mem_name']);
      $Mailer->Subject = TITLE . ' - Recuperação de Senha';
      $Mailer->Body = $body;

      if($Mailer->Send()){
        $jSON['trigger'] = ToastError("Verifique sua caixa de email / spam");
        $jSON['reset']['#Recover'] = true;
      }else{
        $jSON['trigger'] = ToastError("Erro ao enviar email, entre em contato com nossa equipe de suporte!", E_USER_WARNING);
      }
      break;

    case 'recoverToken':
      $Read->FullRead("SELECT * FROM members WHERE mem_tokenrecovery = '{$PostData['token']}'");

      if ($PostData['mem_password'] != $PostData['password_confirm']) {
        $jSON['trigger'] = ToastError("Senhas não coincidem", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      if (!$Read->getResult()) {
        $jSON['trigger'] = ToastError("Token Invalido", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      $user = $Read->getResult()[0];

      unset($Up);
      $Up['mem_tokenrecovery'] = null;
      $Up['mem_password'] = md5($PostData['mem_password']);

      $Update->ExeUpdate("members", $Up, "WHERE mem_id = :id", "id={$user['mem_id']}");

      if ($Update->getResult()) {
        $jSON['trigger'] = ToastError("Senha alterada com sucesso");
        $jSON['location'] = BASE;
      }
      break;
  endswitch;

  //RETORNA O CALLBACK
  if ($jSON):
    echo json_encode($jSON);
  else:
    $jSON['trigger'] = ToastError("Erro desconhecido, contate o desenvolvedor", E_USER_ERROR);
    echo json_encode($jSON);
  endif;
else:
  //ACESSO DIRETO
  die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
