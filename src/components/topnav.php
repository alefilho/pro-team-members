<nav class="navbar navbar-top navbar-expand navbar-light bg-secondary border-bottom">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Search form -->
      <form class="navbar-search navbar-search-dark form-inline mr-sm-3" id="navbar-search-main">
        <div class="form-group mb-0">
          <div class="input-group input-group-alternative input-group-merge">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input class="form-control" placeholder="Pesquisar" type="text">
          </div>
        </div>
        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </form>
      <!-- Navbar links -->
      <ul class="navbar-nav align-items-center ml-md-auto">
        <li class="nav-item d-xl-none">
          <!-- Sidenav toggler -->
          <div class="pr-3 sidenav-toggler sidenav-toggler-light" style="" data-action="sidenav-pin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i style="background-color: #000;" class="sidenav-toggler-line"></i>
              <i style="background-color: #000;" class="sidenav-toggler-line"></i>
              <i style="background-color: #000;" class="sidenav-toggler-line"></i>
            </div>
          </div>
        </li>
        <li class="nav-item d-sm-none">
          <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
            <i class="ni ni-zoom-split-in"></i>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
            <!-- Dropdown header -->
            <div class="px-3 py-3">
              <h6 class="text-sm text-muted m-0">Voce tem <strong class="text-primary">0</strong> notificação(ões).</h6>
            </div>
            <!-- List group -->
            <div class="list-group list-group-flush">
              <!-- <a href="#!" class="list-group-item list-group-item-action">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <img alt="Image placeholder" src="assets/argon/img/theme/team-1.jpg" class="avatar rounded-circle">
                  </div>
                  <div class="col ml--2">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h4 class="mb-0 text-sm">John Snow</h4>
                      </div>
                      <div class="text-right text-muted">
                        <small>2 hrs ago</small>
                      </div>
                    </div>
                    <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                  </div>
                </div>
              </a> -->
            </div>
            <!-- View all -->
            <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">Ver tudo</a>
          </div>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-ungroup"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
            <div class="row shortcuts px-4">
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                  <i class="ni ni-calendar-grid-58"></i>
                </span>
                <small>Calendar</small>
              </a>
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                  <i class="ni ni-email-83"></i>
                </span>
                <small>Email</small>
              </a>
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                  <i class="ni ni-credit-card"></i>
                </span>
                <small>Payments</small>
              </a>
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                  <i class="ni ni-books"></i>
                </span>
                <small>Reports</small>
              </a>
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                  <i class="ni ni-pin-3"></i>
                </span>
                <small>Maps</small>
              </a>
              <a href="#!" class="col-4 shortcut-item">
                <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                  <i class="ni ni-basket"></i>
                </span>
                <small>Shop</small>
              </a>
            </div>
          </div>
        </li> -->
      </ul>
      <ul class="navbar-nav align-items-center ml-auto ml-md-0">
        <li class="nav-item dropdown">
          <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?= BASE; ?>/tim.php?w=100&h=100&src=assets/img/avatar-batman.png">
              </span>
              <div class="media-body ml-2 d-none d-lg-block">
                <span class="mb-0 text-sm  font-weight-bold"><?= $_SESSION['userlogin-member']['mem_name']; ?></span>
              </div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Bem vindo!</h6>
            </div>
            <a onclick="$('#alterarSenha').modal('show');" href="#" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>Alterar Senha</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= BASE; ?>/panel.php?logoff=1" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Sair</span>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="modal fade" id="alterarSenha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Altere a sua senha</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="FormChangePass" class="j_form">
          <input type="hidden" name="AjaxFile" value="Profile">
          <input type="hidden" name="AjaxAction" value="changePass">

          <div class="form-group">
              <label>*Senha atual</label>
              <input class="form-control" type="password" name="old_pwd" value="" required>
          </div>

          <div class="form-group">
              <label>*Nova Senha</label>
              <input class="form-control" type="password" name="new_password" value="" required>
          </div>

          <div class="form-group">
              <label>*Confirme a nova Senha</label>
              <input class="form-control" type="password" name="new_password2" value="" required>
          </div>

          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <input type="submit" name="insert" value="Atualizar senha" class="btn btn-primary" />
        </form>
      </div>
    </div>
  </div>
</div>
