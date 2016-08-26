<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$Auth = Auth::getInstance();
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Mon Profil :: Mon E-Mail</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxProfilEmail">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Mon E-Mail</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="email" class="form-control" name="email" value="<?php echo $Auth->user()->email;?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                            Recevoir une notification lors de l'ajout d'une dédicace
                                        </label>
                                        <input type="radio" class="flat" name="notify_email" value="1" <?php echo($Auth->user()->notify_email?'checked':'');?>/> Activé <br />
                                        <input type="radio" class="flat" name="notify_email" value="0" <?php echo(!$Auth->user()->notify_email?'checked':'');?>/> Désactivé
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success" id="submit">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>