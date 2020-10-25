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
      foreach ($PostData['feed'] as $key => $value) {
        if (empty($value)) {
          $jSON['trigger'] = ToastError("Complete o Feedback", E_USER_WARNING);
          echo json_encode($jSON);
          return;
        }

        $Cr[] = [
          "fee_idtopic" => $key,
          "fee_idmember" => $_SESSION['userlogin-member']['mem_id'],
          "fee_idmembertarget" => $PostData['member_id'],
          "fee_stars" => $value,
          "fee_createdat" => date("Y-m-d H:i:s")
        ];
      }

      foreach ($Cr as $key => $value) {
        $Create->ExeCreate("sessions_topics_feedbacks", $value);
      }

      if ($Create->getResult()) {
        $jSON['trigger'] = ToastError("Salvo com sucesso");
        $jSON['reload'] = true;
        $jSON['modal']['#FeebbackModal'] = 'hide';
      }
      break;

    case 'show':
      $Read->FullRead("SELECT mem_id, mem_name FROM members WHERE mem_id = {$PostData['member_id']}");
      if ($Read->getResult()) {
        $member = $Read->getResult()[0];
      }else {
        return;
      }

      $jSON['content']['#MemberName'] = $member['mem_name'];
      $jSON['content']['#FormFeedback'] = "<input type='hidden' name='AjaxFile' value='Feedbacks'>
        <input type='hidden' name='AjaxAction' value='save'>
        <input type='hidden' name='session_id' value='{$PostData['session_id']}'>
        <input type='hidden' name='member_id' value='{$member['mem_id']}'>
      ";

      $Read->FullRead("SELECT top_id, top_description FROM sessions_topics WHERE top_idsession = {$PostData['session_id']}");
      if ($Read->getResult()) {
        foreach ($Read->getResult() as $key => $value) {
          $jSON['content']['#FormFeedback'] .= "<div class='col-xl-12 col-md-6'>
            <div class='card card-stats'>
              <div class='card-body'>
                <div class='row'>
                  <div class='col'>
                    <h5 class='card-title text-uppercase text-muted mb-0'>{$value['top_description']}</h5>
                  </div>
                </div>
                <p class='mt-3 mb-0 text-sm'>
                  <ul class='ul_stars j_star_feed'>
                    <li><i class='far fa-star' rel='1'></i></li>
                    <li><i class='far fa-star' rel='2'></i></li>
                    <li><i class='far fa-star' rel='3'></i></li>
                    <li><i class='far fa-star' rel='4'></i></li>
                    <li><i class='far fa-star' rel='5'></i></li>
                  </ul>
                  <input type='hidden' name='feed[{$value['top_id']}]' value=''>
                </p>
              </div>
            </div>
          </div>";
        }
      }

      $jSON['content']['#FormFeedback'] .= "<button class='btn btn-secondary' type='button' data-dismiss='modal'>Cancelar</button>
        <button class='btn btn-primary' type='submit'>Salvar</button>
      ";
      $jSON['modal']['#FeebbackModal'] = 'show';
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
