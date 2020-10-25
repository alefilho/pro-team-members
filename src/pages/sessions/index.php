<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Sessões</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-atom"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Sessões</a></li>
              <li class="breadcrumb-item active" aria-current="page">Lista</li>
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
          <h3 class="mb-0">Lista de Sessões</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5"></th>
                <th>Nome</th>
                <th>Data Encerramento</th>
                <th>Tópicos</th>
                <th>Data Criação</th>
                <th>Atualização</th>
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
                	ses_id,
                	ses_name,
                	cla_name,
                	ses_endat,
                	ses_createdat,
                	ses_updatedat,
                	COUNT( DISTINCT top_id ) AS topics,
                	COUNT( DISTINCT fee_id ) AS feedbacks
                FROM
                	sessions
                	LEFT JOIN classes ON cla_id = ses_idclass
                	LEFT JOIN sessions_topics ON top_idsession = ses_id
                	LEFT JOIN sessions_topics_feedbacks ON fee_idtopic = top_id
                WHERE
                	cla_iduser = {$_SESSION['userlogin-member']['mem_idclass']}
                GROUP BY
                	ses_id,
                	ses_name,
                	cla_name,
                	ses_endat,
                	ses_createdat,
                	ses_updatedat
              ";

              $Read->FullRead("{$SQL} LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
              if ($Read->getResult()) {
                foreach ($Read->getResult() as $key => $value) {
                  $btn = "";
                  if (time() < strtotime($value['ses_endat'])) {
                    $btn = "<a class='btn btn-info btn-sm' href='".BASE."/panel.php?page=sessions/teams&id={$value['ses_id']}'>TIME</a>";
                  }

                  echo "<tr class='single_session' id='{$value['ses_id']}'>
                    <td>
                      {$btn}
                    </td>
                    <td>{$value['ses_name']}</td>
                    <td>".(!empty($value['ses_endat']) ? date("d/m/Y H:i:s", strtotime($value['ses_endat'])) : "")."</td>
                    <td>{$value['topics']}</td>
                    <td>".(!empty($value['ses_createdat']) ? date("d/m/Y H:i:s", strtotime($value['ses_createdat'])) : "")."</td>
                    <td>".(!empty($value['ses_updatedat']) ? date("d/m/Y H:i:s", strtotime($value['ses_updatedat'])) : "")."</td>
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
