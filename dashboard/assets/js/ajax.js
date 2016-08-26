$(document).ready(function(){
    function notify(type, message){
            new PNotify({
                delay: 3000,
                type: type,
                text: message,
                nonblock: {
                    nonblock: true
                }
            });
    }
    $('.dediValidate').click(function(event){
        event.preventDefault();
        var $this = $(this);
        $.ajax({
            method: "GET",
            url: 'AjaxProcess.php?dedivalidate=' + $this.attr("id")
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == 'ok'){
                    $this.parents("tr").hide(1200);
                    notify('success', 'Dédicace validée avec succès!');
                } else {
                    console.log(data);
                    notify('error', 'Erreur lors de la validation de la dédicace');
                }
            })
            .fail(function(){
                notify('error', 'Impossible de valider la dédicace');
            });
    });
    $('.dediDelete').click(function(event){
        event.preventDefault();
        var $this = $(this);
        if(confirm('Etes-vous sur de vouloir supprimer cette dédicace ?')) {
            $.ajax({
                method: "GET",
                url: 'AjaxProcess.php?dedidelete=' + $this.attr("id")
            })
                .done(function (data) {
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        $this.parents("tr").hide(1200);
                        notify('success', 'Dédicace supprimée avec succès!');
                    } else {
                        notify('error', 'Impossible de supprimer la dédicace');
                    }
                })
                .fail(function () {
                    notify('error', 'Impossible de supprimer la dédicace');
                });
        }
    });
    $(".ajaxDediEdit").submit(function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "AjaxProcess.php?DediEdit",
            data: $(".ajaxDediEdit").serialize()
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    notify('success', 'Dédicace modifiée avec succès');
                } else {
                    notify('error', 'Impossible de sauvegarder vos modifications');
                }
            })
            .fail(function(){
                notify('error', 'Impossible de sauvegarder vos modifications');
            });
    });
    $('.banipDelete').click(function(event){
        event.preventDefault();
        var $this = $(this);
        if(confirm('Etes-vous sur de vouloir supprimer cette IP ?')) {
            $.ajax({
                method: "GET",
                url: 'AjaxProcess.php?banipdelete=' + $this.attr("id")
            })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        $this.parents("tr").hide(1200);
                        notify('success', 'IP supprimée avec succès!');
                    } else {
                        notify('error', 'Impossible de supprimer l\'IP');
                    }
                })
                .fail(function () {
                    notify('error', 'Impossible de supprimer l\'IP');
                });
        }
    });
    $('#addIP').click(function(event){
        event.preventDefault();
        $("#formip").show('slow');
        $("#formip").submit(function(event2){
            event2.preventDefault();
            $.ajax({
                method: 'GET',
                url: 'AjaxProcess.php?addIP=' + $('#ip').val()
            })
                .done(function(data){
                    var data = JSON.parse(data);
                    if(data.status == 'ok'){
                        $('#formip').hide('slow');
                        notify('success', 'Adresse IP Ajoutée !<br />La page va se réactualiser dans quelques secondes.');
                        setTimeout(function() {
                            location.reload(true);
                        }, 3200);
                    } else {
                        notify('error', 'Impossible d\'ajouter l\'adresse IP');
                    }
                })
                .fail(function(){
                    notify('error', 'Impossible d\'ajouter l\'adresse IP');
                });
        });
    });
    $('#addUser').click(function(event){
        event.preventDefault();
        $("#divadduser").show('slow');
        $("#formadduser").submit(function(event2){
            event2.preventDefault();
            $.ajax({
                method: 'POST',
                url: 'AjaxProcess.php?action=adduser',
                data: $("#formadduser").serialize()
            })
                .done(function(data){
                    var data = JSON.parse(data);
                    if(data.status == 'ok'){
                        $('#divadduser').hide('slow');
                        notify('success', 'Utilisateur créé avec succès !<br />La page va se rafraichir dans quelques secondes.');
                        setTimeout(function() {
                            location.reload(true);
                        }, 3400);
                    } else {
                        $.each(data.message, function(key, value){
                            notify('error', value);
                        });
                    }
                })
                .fail(function(){
                    notify('error', 'Création de l\'utilisateur impossible');
                });
        });
    });
    $('.userDelete').click(function(event){
        event.preventDefault();
        var $this = $(this);
        if(confirm('Etes-vous sur de vouloir supprimer cet utilisateur ?')) {
            $.ajax({
                method: "GET",
                url: 'AjaxProcess.php?userdelete=' + $this.attr("id")
            })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        $this.parents("tr").hide(1200);
                        notify('success', 'Utilisateur supprimé avec succès!');
                    } else {
                        notify('error', 'Impossible de supprimer l\'utilisateur');
                    }
                })
                .fail(function() {
                    notify('error', 'Impossible de supprimer l\'utilisateur');
                });
        }
    });
    $('.ajaxSettings').submit(function(event){
        event.preventDefault();
        $.ajax({
            method:'POST',
            url: 'AjaxProcess.php?action=savesettings',
            data: $('.ajaxSettings').serialize()
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    notify('success', 'Configuration enregistrée !');
                } else {
                    notify('error', 'Enregistrement impossible.');
                }
            })
            .fail(function(){
                notify('error', 'Enregistrement impossible.');
            });
    });
    $('#ajaxGetCredentialsApi').click(function(event){
        event.preventDefault();
        $.ajax({
            method:'GET',
            url: 'AjaxProcess.php?action=getcredentialsapi'
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    notify('success', 'Identifiants API Générés ! <br />Rechargement de la page en cours...');
                    setTimeout(function() {
                        location.reload(true);
                    }, 3400);
                } else {
                    notify('error', 'La génération des identifiants à échoué, veuiller réessayer.');
                }
            })
            .fail(function(){
                notify('error', 'Impossible de contacter le serveur');
            });
    });
    $('#maintenance').click(function(event){
        event.preventDefault();
        $.ajax({
            method: 'GET',
            url: 'AjaxProcess.php?maintenance=' + $('#maintenance').val()
        })
            .done(function(){
                if($('#maintenance').val()==1){
                    $('#maintenance').html('Désactiver Maintenance').removeClass('btn-danger').addClass('btn-success');
                    $('#maintenance').val(0);
                    notify('notice', 'Maintenance Activée');
                } else {
                    $('#maintenance').html('Activer Maintenance').removeClass('btn-success').addClass('btn-danger');
                    $('#maintenance').val(1);
                    $('#maintenance_div').hide('slow');
                    notify('info', 'Maintenance Désactivée');
                }
            })
            .fail(function(){
                notify('error', 'Mode maintenance inaccessible');
            });
    });
    $("#execUpdate").click(function(event){
        event.preventDefault();
        $("#execUpdate").prop('disabled', true);
        $.ajax({
            method: "GET",
            url: "AjaxProcess.php?Updater=execute"
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    $("#updateDiv").html('Mise à jour effectuée avec succès!<br />Votre script est maintenant à jour ;)').addClass('alert-success').show("slow");
                } else {
                    $("#updateDiv").html('Impossible d\'appliquer la mise à jour, veuillez réessayer ultérieurement.').addClass('alert-danger').show("slow");
                }
            })
            .fail(function(){
                $("#updateDiv").html('Impossible d\'appliquer la mise à jour, veuillez réessayer ultérieurement2.').addClass('alert-danger').show("slow");
            });
    });
    $(".ajaxProfilEmail").submit(function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "AjaxProcess.php?ProfilEdit=Email",
            data: $(".ajaxProfilEmail").serialize()
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    notify('success', 'Mail modifié avec succès');
                } else {
                    notify('error', 'Impossible de sauvegarder vos modifications');
                }
            })
            .fail(function(){
                notify('error', 'Impossible de sauvegarder vos modifications');
            });
    });
    $(".ajaxProfilPassword").submit(function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "AjaxProcess.php?ProfilEdit=Password",
            data: $(".ajaxProfilPassword").serialize()
        })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == 'ok'){
                    $("#submit").prop('disabled', true);
                    notify('success', 'Mot de passe modifié avec succès !');
                } else {
                    $.each(data.message, function(key, value){
                        notify('error', value);
                    });
                }
            })
            .fail(function(){
                notify('error', 'Impossible de sauvegarder votre nouveau mot de passe');
            });
    });
    $('.dediBanIp').click(function(event){
        event.preventDefault();
        if(confirm('Etes-vous sur de vouloir bannir cet IP ?')) {
            $.ajax({
                method: 'GET',
                url: 'AjaxProcess.php?addIP=' + $('.dediBanIp').val()
            })
                .done(function(data){
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        notify('success', 'Adresse IP Bannie !');
                    } else {
                        notify('error', 'Impossible de bannir l\'adresse IP');
                    }
                })
                .fail(function () {
                    notify('error', 'Impossible de bannir l\'adresse IP');
                });
        }
    });
    $('#getCaptchaParams').click(function(event){
        event.preventDefault();
        $("#getCaptchaParams").hide('slow');
        $("#CaptchaParamsDiv").show('slow');
    });
    $('.pluginInstall').click(function(e){
        e.preventDefault();
        var $this = $(this);
        if(confirm('Etes-vous sur de vouloir installer le plugin ' + $this.attr('id') + ' ?')) {
            $.ajax({
                    method: 'GET',
                    url: 'AjaxProcess.php?pluginInstall=' + $this.attr('id')
                })
                .done(function(data){
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        notify('success', 'Plugin installé avec succès !<br />Rafraichissement de la page en cours...');
                        setTimeout(function() {
                            location.reload(true);
                        }, 3400);
                    } else {
                        notify('error', 'Erreur lors de l\'installation du plugin');
                    }
                })
                .fail(function () {
                    notify('error', 'Impossible d\'installer le plugin');
                });
        }
    });
    $('.pluginUninstall').click(function(e){
        e.preventDefault();
        var $this = $(this);
        if(confirm('Etes-vous sur de vouloir désinstaller le plugin ' + $this.attr('id') + ' ?')) {
            $.ajax({
                    method: 'GET',
                    url: 'AjaxProcess.php?pluginUninstall=' + $this.attr('id')
                })
                .done(function(data){
                    var data = JSON.parse(data);
                    if (data.status == 'ok') {
                        notify('success', 'Plugin désinstallé avec succès !<br />Rafraichissement de la page en cours...');
                        setTimeout(function() {
                            location.reload(true);
                        }, 3400);
                    } else {
                        notify('error', 'Erreur lors de la désinstallation du plugin');
                    }
                })
                .fail(function () {
                    notify('error', 'Impossible de désinstaller le plugin');
                });
        }
    });
    $(".ajaxPluginConf").submit(function(event){
        event.preventDefault();
        $.ajax({
                method: "POST",
                url: "AjaxProcess.php?pluginConf=Save",
                data: $(".ajaxPluginConf").serialize()
            })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.status == "ok"){
                    notify('success', 'Configuration enregistrée !');
                } else {
                    notify('error', 'Impossible de sauvegarder vos modifications');
                }
            })
            .fail(function(){
                notify('error', 'Impossible de sauvegarder vos modifications');
            });
    });
});