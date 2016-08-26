<?php
namespace Dedinomy;

if (!defined('SECURE_LINK')) die('Direct access is not authorized');
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
$Plugins = Plugin::getInstance();
if (!isset($_GET['name']) OR empty($_GET['name'])) {
    Utils::redirect('?action=home');
    exit();
} else {
    if (!$pluginData = $Plugins->getConfig($_GET['name'])) {
        Utils::redirect('?action=home');
        exit();
    }
}
?>
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Plugin <?php echo $pluginData->name; ?> :: Configuration</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left ajaxPluginConf">
                        <input type="hidden" name="id" value="<?php echo $pluginData->id; ?>">
                        <?php
                        $paramsData = json_decode($pluginData->params);
                        foreach ($paramsData as $key=>$value) {
                            echo '<div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">' . $key . '</label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">';
                                echo '<input type="text" class="form-control" name="data[' . $key . ']" value="' . $value . '" required>';
                            echo '</div></div>';
                        }
                        ?>
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