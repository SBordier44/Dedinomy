<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
if(!isset($_GET['id'])){
    Utils::redirect('?action=home');
}
$Database = Database::getInstance();
$data = $Database->query('SELECT * FROM dn_messages WHERE id = ?', [$_GET['id']])->fetch();
if(empty($data)) {Utils::redirect('?action=home');}
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Dédicaces :: Edition</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal form-label-left ajaxDediEdit">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pseudo</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="nickname" value="<?php echo $data->nickname;?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Texte de la dédicace</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="message" value="<?php echo $data->message;?>" required>
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