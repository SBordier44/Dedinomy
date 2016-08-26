<?php
namespace Dedinomy;

if (!defined('SECURE_LINK')) die('Direct access is not authorized');
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
$plugins = Plugin::getInstance();
?>
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Plugins :: Gestion</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content table-responsive">
                    <?php if (count($plugins->getPlugins()) > 0): ?>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Auteur</th>
                                <th>Description</th>
                                <th>Version</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($plugins->getPlugins() as $plugin): ?>
                                <tr>
                                    <td><?php echo $plugin->Name; ?></td>
                                    <td><?php echo $plugin->Infos->Author; ?></td>
                                    <td><?php echo $plugin->Infos->Description; ?></td>
                                    <td><?php echo $plugin->Infos->Version; ?></td>
                                    <td>
                                        <?php if ($plugin->isInstalled): ?>
                                            <button type="button"
                                                    class="btn btn-round btn-danger btn-xs pluginUninstall"
                                                    id="<?php echo $plugin->Name; ?>">Désinstaller
                                            </button>
                                            <button type="button" class="btn btn-round btn-info btn-xs"
                                                    onclick="location.href='?action=plugin_config&name=<?php echo $plugin->Name; ?>'">
                                                Configuration
                                            </button>
                                            <?php
                                        else: ?>
                                            <button type="button" class="btn btn-round btn-success btn-xs pluginInstall"
                                                    id="<?php echo $plugin->Name; ?>">Installer
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">Aucun plugin disponible</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>