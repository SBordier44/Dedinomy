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
                                <h2>Configuration :: API</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <?php if(!$Settings->get('api_key') || !$Settings->get('secret_key')){
                                    ?>
                                    <button class="btn btn-info" id="ajaxGetCredentialsApi">Générer des identifiants API</button>
                                <?php } else { ?>
                                    <ins>Vos identifiants API</ins><br />
                                    <ins>ApiKey :</ins>  <span style="font-weight: bold;" id="apikey"><?php echo $Settings->get('api_key');?></span><br />
                                    <ins>SecretKey :</ins>  <span style="font-weight: bold;" id="secretkey"><?php echo $Settings->get('secret_key');?></span><br /><br />
                                    <button class="btn btn-info" id="ajaxGetCredentialsApi">Générer de nouveaux identifiants API</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>