<?php
session_start();
require_once '../../_app/Config.inc.php';

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
    case 'save':
      $id = isset($PostData['id']) ? $PostData['id'] : null;
      unset($PostData['id']);

      if (!empty($id)) {
        $Update->ExeUpdate("teams", $PostData, "WHERE tea_id = :id", "id={$id}");
      }else {
        $PostData['tea_createdat'] = date("Y-m-d H:i:s");
        $PostData['tea_idmember'] = $_SESSION['userlogin-member']['mem_id'];

        $Create->ExeCreate("teams", $PostData);

        $Cr['tme_idteam'] = $Create->getResult();
        $Cr['tme_idmember'] = $PostData['tea_idmember'];
        $Create->ExeCreate("teams_members", $Cr);
      }

      if ($Create->getResult() || $Update->getResult()) {
        $jSON['trigger'] = ToastError("Time salvo com sucesso");
        $jSON['location'] = BASE . '/panel.php?page=teams/index';
        $jSON['reset']['#TeamForm'] = true;
      }
      break;

    case 'in':
      $Cr['tme_idteam'] = $PostData['id'];
      $Cr['tme_idmember'] = $_SESSION['userlogin-member']['mem_id'];
      $Create->ExeCreate("teams_members", $Cr);
      $jSON['reload'] = true;
      break;

    case 'out':
      $Delete->ExeDelete("teams_members", "WHERE tme_idteam = :idTeam AND tme_idmember = :idMember", "idTeam={$PostData['id']}&idMember={$_SESSION['userlogin-member']['mem_id']}");
      $jSON['reload'] = true;
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
