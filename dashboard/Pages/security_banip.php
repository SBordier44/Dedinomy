<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$paginate = Paginator::getInstance();
$paginate->init('SELECT * FROM dn_banip ORDER BY id DESC', null);
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Sécurité :: Gestion des IP Bannies</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><button id="addIP"><i class="fa fa-plus text-success"></i> Ajouter une IP</button>
                                    </li>
                                </ul>
                                <div class="clearfix"></div><small>C'est ici que sont listés les adresses IP bloqués pour l'envoi de dédicaces ainsi que pour l'administration.</small>
                            </div>
                            <div class="x_content table-responsive">
                                <?php if($paginate->getData()->total):?>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date d'ajout</th>
                                        <th>IP</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($paginate->getData()->data as $data):?>
                                    <tr>
                                        <th scope="row"><?php echo Utils::convertDate($data->created); ?></th>
                                        <td><?php echo $data->ip; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-round btn-danger btn-xs banipDelete" id="<?php echo $data->id; ?>">Supprimer</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                    <div class="alert alert-info">Aucune IP Bannie actuellement</div>
                                <?php endif; ?>
                                <?php echo $paginate->createLinks(10); ?>
                            </div>
                            <div class="x_content" style="display: none;" id="formip">
                                <form class="form-horizontal form-label-left">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Adresse IP</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" id="ip" required>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">Ajouter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>