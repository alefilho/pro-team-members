<?php
if (isset($GetId)) {
  $Read->FullRead("SELECT * FROM sessions WHERE ses_id = {$GetId}");

  if ($Read->getResult()) {
    $Register = $Read->getResult()[0];
  }else {
    echo triggerRegisterNotFound();
    return;
  }
}else {
  echo triggerRegisterNotFound();
  return;
}
?>
<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Sessões</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-atom"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Sessão</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?= $Register['ses_name']; ?></li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <!-- <a href="#" class="btn btn-sm btn-neutral">New</a>
          <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Seu Time</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5"></th>
                <th>Nome</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody class="list">
              <?php
              if (!isset($GetPag)) {
                $GetPag = 1;
              }
              $Pager = new Pager(BASE . '/panel.php?page=sessions/index&pag=');
              $Pager->ExePager($GetPag, LIMIT_PAGE);
              $SQL = "SELECT
                	mem_id,
                	mem_name
                FROM
                	members
                	RIGHT JOIN teams_members ON tme_idmember = mem_id
                WHERE
                	tme_idteam IN (SELECT tme_idteam FROM teams_members WHERE tme_idmember = {$_SESSION['userlogin']['use_id']})
                	AND mem_id <> {$_SESSION['userlogin']['use_id']}
                GROUP BY
                	mem_id,
                	mem_name
              ";

              $Read->FullRead("{$SQL} LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
              if ($Read->getResult()) {
                $Read2 = new Read;

                foreach ($Read->getResult() as $key => $value) {
                  $Read2->FullRead("SELECT
                      fee_createdat
                    FROM
                      sessions_topics_feedbacks
                      LEFT JOIN sessions_topics ON top_id = fee_idtopic
                    WHERE
                      top_idsession = {$Register['ses_id']}
                      AND fee_idmember = {$_SESSION['userlogin-member']['mem_id']}
                      AND fee_idmembertarget = {$value['mem_id']}"
                  );
                  $btn = "";
                  $fee_createdat = "";
                  if (!$Read2->getResult()) {
                    $btn = "<button type='button' ajaxfile='Teams' ajaxaction='out' ajaxdata='id={$value['mem_id']}' confirm='true' class='btn btn-info btn-sm j_ajax_generic'>DAR FEEDBACK</button>";
                  }else {
                    $fee_createdat = $Read2->getResult()[0]['fee_createdat'];
                  }

                  echo "<tr class='single_session' id='{$value['mem_id']}'>
                    <td>{$btn}</td>
                    <td>{$value['mem_name']}</td>
                    <td>".(!empty($value['ses_createdat']) ? date("d/m/Y H:i:s", strtotime($fee_createdat)) : "")."</td>
                  </tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
          <?php
          $Pager->ExePaginator(null, null, null, $SQL);
          echo $Pager->getPaginator();
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php include 'src/components/footer.php'; ?>
</div>
