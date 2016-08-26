<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>{SCRIPT_NAME}</title>
    <link href="assets/css/poster.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
    <div class="container theme-showcase" role="main">
        <div class="text-center">
            <h1></h1>
        </div>
        <div class="row text-center col-md-4 col-md-offset-4 col-xs-4 col-xs-offset-4 col-lg-4 col-lg-offset-4">
            {if !$settings->get('maintenance_status')}
            <form id="poster" method="post">
                <input type="hidden" value="{$token}" name="token">
                <div class="form-group">
                    <label for="nickname">Ton Pseudo</label>
                    <input type="nickname" class="form-control" id="nickname" name="nickname" required>
                </div>
                <div class="form-group">
                    <label for="message">Ta Dédicace</label>
                    {if $settings->get('form_caracters')}
                        <textarea class="form-control" name="message" id="message" maxlength="{$settings->get('form_caracters')}" required></textarea>
                        <small><i>(Limité à {$settings->get('form_caracters')} caractères)</i></small>
                    {else}
                        <textarea class="form-control" name="message" id="message" required></textarea>
                    {/if}
                </div>
                {if $settings->get('recaptcha_status') and ($settings->get('recaptcha_sitekey')!=null) and ($settings->get('recaptcha_secret')!=null)}
                    <div class="g-recaptcha" data-sitekey="{$settings->get('recaptcha_sitekey')}"></div><br />
                {/if}
                <button type="submit" class="btn btn-info btn-sm">Envoyer</button>
            </form>
            <div class="alert" style="display: none;"></div>
            {else}
                <div class="alert alert-info">{$maintenance_msg}</div>
            {/if}
        </div>
    </div>
    <script src="assets/js/jquery-2.1.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    {if $settings->get('recaptcha_status') and ($settings->get('recaptcha_sitekey')!=null) and ($settings->get('recaptcha_secret')!=null)}
        <script src='https://www.google.com/recaptcha/api.js?hl=fr'></script>
    {/if}
    <script>
        /** NE PAS TOUCHER! PARTIE AJAX **/
        $("#poster").submit(function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: window.location,
                data: $("#poster").serialize()
            })
                .done(function(data){
                    $("#poster").hide('slow');
                    if(data.status == "ok") {
                        if({$settings->get('dedi_autopublish')}){
                            $(".alert").html("{$published_msg}").addClass('alert-success').show('slow');
                        } else {
                            $(".alert").html("{$moderated_msg}").addClass('alert-success').show('slow');
                        }
                    } else if (data.status == "noktoken" || data.status == "nokform") {
                        $.each(data.message, function (key, value) {
                            $(".alert").html(value).addClass('alert-warning').show('slow');
                        });
                    } else if (data.status == "nokcaptcha") {
                        $(".alert").html("Erreur lors de la validation du captcha, veuillez réessayer. <br /><u>Code Erreur :</u> " + data.message).addClass('alert-warning').show('slow');
                    } else {
                        $(".alert").html("Une erreur s'est produite, veuillez réessayer.").addClass('alert-warning').show('slow');
                    }
                })
                .fail(function(message){
                    console.log(message);
                    $(".alert").html("Impossible d'envoyer votre message, veuillez réessayer.").addClass('alert-warning').show('slow');
                });
        });
    </script>
</body>
</html>