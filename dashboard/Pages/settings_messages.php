<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$Settings = Settings::getInstance();
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Configuration :: Messages</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxSettings">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Message affiché lorsque la maintenance est activée</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <textarea name="maintenance_msg" id="descr1" required><?php echo $Settings->get('maintenance_msg');?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Message affiché lorsqu'une dédicace est envoyée et automatique publiée</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <textarea name="published_msg" id="descr2" required><?php echo $Settings->get('published_msg');?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Message affiché lorsqu'une dédicace est envoyée et soumise à validation de l'équipe</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <textarea name="moderated_msg" id="descr3" required><?php echo $Settings->get('moderated_msg');?></textarea>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>