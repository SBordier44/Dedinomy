<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$Session = Session::getInstance();
$Auth = Auth::getInstance();
$Settings = Settings::getInstance();
$Updater = Updater::getInstance();
if ($Updater->update->checkUpdate()) {
    $update = ($Updater->update->newVersionAvailable()) ? true : false;
} else $update = false;
$Database = Database::getInstance();
$dedi_a_valider = $Database->query('SELECT * FROM dn_messages WHERE online="0"')->rowCount();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SCRIPT_NAME ?> :: Administration / Modération</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/icheck/flat/green.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/js/cleditor/jquery.cleditor.css">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <script src="assets/js/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="" class="site_title"><i class="fa fa-paw"></i> <span><?php echo SCRIPT_NAME.' '.Utils::version();?></span></a>
                </div>
                <div class="clearfix"></div>
                <div class="profile">
                    <div class="profile_pic">
                        <img src="assets/img/user.png" alt="<?php echo ucfirst($Auth->user()->username); ?>" class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Bienvenue,</span>
                        <h2><?php echo ucfirst($Auth->user()->username); ?></h2>
                    </div>
                </div>
                <br />
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <br />
                        <?php if($update && $Auth->isAdmin()):?>
                        <a href="?action=settings_update"><span class="badge bg-blue" style="margin-left:20px;">Mise à jour disponible !</span></a>
                        <br /><br />
                        <?php endif;?>
                        <h3>Menu</h3>
                        <ul class="nav side-menu">
                            <li><a href="?action=home"><i class="fa fa-home"></i> Accueil</a>
                            </li>
                            <li><a><i class="fa fa-comment"></i> Dédicaces&nbsp;
                                    <?php if($dedi_a_valider){?>
                                        <span class="badge bg-blue"><?php echo $dedi_a_valider; ?> En Attente</span>
                                    <?php } ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="?action=dedi_offline">Hors ligne&nbsp;<?php if($dedi_a_valider){?>
                                                <span class="badge bg-blue">*</span>
                                            <?php } ?></a>
                                    </li>
                                    <li><a href="?action=dedi_online">En ligne</a>
                                    </li>
                                </ul>
                            </li>
                            <?php if($Auth->isAdmin()){?>
                            <li><a><i class="fa fa-users"></i> Utilisateurs <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="?action=users_admin">Administrateurs</a>
                                    </li>
                                    <li><a href="?action=users_modo">Modérateurs</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-flag"></i> Sécurité <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="?action=security_banip">IP Bannies</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cogs"></i> Configuration <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="?action=settings_global">Globale</a>
                                    </li>
                                    <li><a href="?action=settings_displaydedi">Affichage Dédicaces</a>
                                    </li>
                                    <li><a href="?action=settings_displayform">Affichage Formulaire</a>
                                    </li>
                                    <li><a href="?action=settings_api">API</a>
                                    </li>
                                    <li><a href="?action=settings_admin">Administration</a>
                                    </li>
                                    <li><a href="?action=settings_messages">Messages</a>
                                    </li>
                                </ul>
                                <li><a href="?action=settings_update"><i class="fa fa-refresh"></i> Mise à jour</a></li>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Fermer la session" href="logout.php">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="top_nav">
            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="assets/img/user.png" alt=""><?php echo ucfirst($Auth->user()->username); ?>
                                <span class="fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                <li><a href="?action=profil_password"> Mon mot de passe </a>
                                </li>
                                <li><a href="?action=profil_email"> Mon Email </a>
                                </li>
                                <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Déconnexion </a>
                                </li>
                            </ul>
                        </li><br />
                        <?php if($Auth->isAdmin()){?>
                        <?php if(!$Settings->get('maintenance_status')):?>
                        <li><button type="button" class="btn btn-round btn-danger" id="maintenance" value="1">Activer Maintenance</button></li>
                        <?php else: ?>
                        <li><button type="button" class="btn btn-round btn-success" id="maintenance" value="0">Désactiver Maintenance</button></li>
                        <?php endif;?>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="right_col" role="main">
            <?php if($Settings->get('maintenance_status')):?>
                <div class="alert alert-warning" id="maintenance_div"><i class="fa fa-warning"></i> Maintenance en cours</div>
            <?php endif;?>
            <?php echo $Session->getNotify();?>
            <?php echo Pager::content();?>
            <footer>
                <div class="">
                    <p class="pull-right"><?php echo COPY ?>
                    </p>
                </div>
                <div class="clearfix"></div>
            </footer>
        </div>
    </div>
</div>
<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/nicescroll/jquery.nicescroll.min.js"></script>
<script src="assets/js/icheck/icheck.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/notify/pnotify.core.js"></script>
<script src="assets/js/notify/pnotify.buttons.js"></script>
<script src="assets/js/notify/pnotify.nonblock.js"></script>
<script src="assets/js/cleditor/jquery.cleditor.min.js"></script>
<script src="assets/js/ajax.js"></script>
<script>
    $(document).ready(function () {
        $("#descr1").cleditor();
        $("#descr2").cleditor();
        $("#descr3").cleditor();
    });
</script>
</body>
</html>