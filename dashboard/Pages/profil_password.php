<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Mon Profil :: Modifier mon Mot de Passe</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxProfilPassword">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nouveau mot de passe</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="password" class="form-control" name="password" value="" required>
                                        </div>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nouveau mot de passe <small>(Confirmation)</small></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="password" class="form-control" name="password_confirm" value="" required>
                                        </div>
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