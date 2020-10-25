<?php
$pages = [
  "teams" => [
    "teams/index",
    "teams/form"
  ],
  "sessions" => [
    "sessions/index",
    "sessions/teams"
  ],
];
?>

<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-dark" id="sidenav-main">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <div class="sidenav-header d-flex align-items-center">
      <a class="navbar-brand" href="<?= BASE; ?>">
        <img src="assets/img/logo2.svg" class="navbar-brand-img" alt="...">
      </a>
      <div class="ml-auto">
        <!-- Sidenav toggler -->
        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-inner">
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?= (((!empty($GetURL) && in_array($GetURL, $pages['teams'])) || empty($GetURL)) ? "active-clean" : ""); ?>" href="<?= BASE; ?>/panel.php?page=teams/index">
              <i class="fas fa-users"></i>
              <span class="nav-link-text">Times</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (((!empty($GetURL) && in_array($GetURL, $pages['sessions']))) ? "active-clean" : ""); ?>" href="<?= BASE; ?>/panel.php?page=sessions/index">
              <i class="fas fa-atom" style="color: #2dce89;"></i>
              <span class="nav-link-text">Sessões</span>
              <?php
              $Read->FullRead("SELECT
                  COUNT(ses_id) AS count
                FROM sessions
                	LEFT JOIN classes ON cla_id = ses_idclass
                WHERE
                	cla_iduser = {$_SESSION['userlogin-member']['mem_idclass']}"
              );
              ?>
              <span style="padding-left: 15px;"><span class="badge badge-primary"><?= $Read->getResult()[0]['count']; ?></span></span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
