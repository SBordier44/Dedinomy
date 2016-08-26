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
                                <h2>Configuration :: Administration</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxSettings">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre de résultats par page</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select class="form-control" name="admin_perpage">
                                                <option value="10" <?php echo($Settings->get('admin_perpage')==10)?'selected':''?>>10</option>
                                                <option value="20" <?php echo($Settings->get('admin_perpage')==20)?'selected':''?>>20</option>
                                                <option value="30" <?php echo($Settings->get('admin_perpage')==30)?'selected':''?>>30</option>
                                                <option value="50" <?php echo($Settings->get('admin_perpage')==50)?'selected':''?>>50</option>
                                                <option value="100" <?php echo($Settings->get('admin_perpage')==100)?'selected':''?>>100</option>
                                            </select>
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