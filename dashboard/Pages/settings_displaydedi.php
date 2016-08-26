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
                                <h2>Configuration :: Affichage Dédicaces</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxSettings">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Limite de dédicaces affichés</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="dedi_displaylimit" value="<?php echo $Settings->get('dedi_displaylimit');?>" required>
                                            <small>(0 = Illimité / Attention, si vous avez un grand nombre de dédicaces, cela peut ralentir l'affichage)</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                            Affichage de la date et l'heure des dédicaces
                                        </label>
                                        <input type="radio" class="flat" name="dedi_displaydate" value="1" <?php echo($Settings->get('dedi_displaydate')?'checked':'');?>/> Activé <br />
                                        <input type="radio" class="flat" name="dedi_displaydate" value="0" <?php echo(!$Settings->get('dedi_displaydate')?'checked':'');?>/> Désactivé
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