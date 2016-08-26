<?php
namespace Dedinomy;

if (!defined('SECURE_LINK')) die('Direct access is not authorized');
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
                    <h2>Configuration :: Affichage Formulaire</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left ajaxSettings">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Caractères max autorisés</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" name="form_caracters"
                                       value="<?php echo $Settings->get('form_caracters'); ?>" required>
                                <small>(0 = Illimité)</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                Protection Anti-Spam Recaptcha
                                <a href="javascript:;" id="getCaptchaParams">
                                    <button class="btn btn-round btn-info btn-xs">Paramètres ReCaptcha</button>
                                </a>
                            </label>
                            <input type="radio" class="flat" name="recaptcha_status"
                                   value="1" <?php echo($Settings->get('recaptcha_status') ? 'checked' : ''); ?>/>
                            Activé <br/>
                            <input type="radio" class="flat" name="recaptcha_status"
                                   value="0" <?php echo(!$Settings->get('recaptcha_status') ? 'checked' : ''); ?>/>
                            Désactivé<br/>
                            <div id="CaptchaParamsDiv" style="display: none;">
                                <div class="form-group">
                                    <a href="https://www.google.com/recaptcha/admin" target="_blank"
                                       class="alert-danger">Récupérer / Créer vos identifiants sur Google Recaptcha</a>
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Clé du site</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control" name="recaptcha_sitekey"
                                               value="<?php echo $Settings->get('recaptcha_sitekey'); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Clé Secrète</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control" name="recaptcha_secret"
                                               value="<?php echo $Settings->get('recaptcha_secret'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                Modération requise
                            </label>
                            <input type="radio" class="flat" name="dedi_autopublish"
                                   value="0" <?php echo(!$Settings->get('dedi_autopublish') ? 'checked' : ''); ?>/>
                            Activé <br/>
                            <input type="radio" class="flat" name="dedi_autopublish"
                                   value="1" <?php echo($Settings->get('dedi_autopublish') ? 'checked' : ''); ?>/>
                            Désactivé
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