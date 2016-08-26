<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$Updater = Updater::getInstance();
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Configuration :: Mise à jour</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><span class="text-info"><a href="http://forum.nubox.fr" target="_blank">>> Consulter les notes de mise à jour <<</a>
                                    </span></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <cite>Version actuelle :</cite> <srong><?php echo VERSION; ?></srong><br /><br />
                                <?php
                                if ($Updater->update->checkUpdate()){
                                    if ($Updater->update->newVersionAvailable()){?>
                                        <div class="alert alert-success">Des mises à jour sont disponibles !</div>
                                        <cite>Version disponible :</cite> <strong><?php echo $Updater->update->getLatestVersion();?></strong><br /><br />
                                        <button type="button" class="btn btn-round btn-info" id="execUpdate">Mettre à jour maintenant</button>
                                        <div style="display:none" class="alert" id="updateDiv"></div>
                                    <?php } else { ?>
                                        <div class="alert alert-info">Votre script est à jour!</div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="alert alert-info">Aucune mise à jour disponible.</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>