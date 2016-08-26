<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$paginate = Paginator::getInstance();
$paginate->init('SELECT * FROM dn_users WHERE grade="modo" ORDER BY id DESC', null);
$Auth = Auth::getInstance();
$Auth->restrictAdmin();
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Utilisateurs :: Liste des Modérateurs</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><button id="addUser"><i class="fa fa-plus text-success"></i> Ajouter un Modérateur</button>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content table-responsive">
                                <?php if($paginate->getData()->total):?>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Identifiant</th>
                                        <th>EMail</th>
                                        <th>Date Création</th>
                                        <th>Dern.Accès</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($paginate->getData()->data as $data):?>
                                    <tr>
                                        <th scope="row"><?php echo $data->username; ?></th>
                                        <td><?php echo $data->email; ?></td>
                                        <td><?php echo Utils::convertDate($data->created); ?></td>
                                        <td><?php echo Utils::convertDate($data->last_access); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-round btn-danger btn-xs userDelete" id="<?php echo $data->id; ?>">Supprimer</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                    <div class="alert alert-info">Aucun modérateur existant</div>
                                <?php endif; ?>
                                <?php echo $paginate->createLinks(10); ?>
                            </div>
                            <div class="x_content" style="display: none;" id="divadduser">
                                <form class="form-horizontal form-label-left" id="formadduser">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Identifiant</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="username" required>
                                            <input type="hidden" name="grade" value="modo">
                                        </div>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">EMail</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Mot de passe</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Mot de passe <small>(confirmation)</small></label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="password" class="form-control" name="password_confirm" required>
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