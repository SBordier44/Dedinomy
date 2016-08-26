<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>{SCRIPT_NAME}</title>
    <link rel="stylesheet" href="assets/css/dedi.css">
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="marquee">
    {foreach $messages as $message}
        {if $display_datetime}
            <span class="datetime">{$message->created|date_format:"%d/%m/%Y à %H:%M"} => </span></if>
        {/if}
        <span class="pseudo">{$message->nickname|capitalize}</span>
        <span class="message"><img src="assets/img/quote.gif" border="0" style="vertical-align:middle;" height="20px">{$message->message|capitalize}<img src="assets/img/quote2.gif" border="0" height="20px" style="vertical-align:middle;"></span>
        <img src="assets/img/star.png" border="0" class="spacer" style="vertical-align: middle;">
    {/foreach}
</div>
<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/js/jquery.marquee.min.js"></script>
<script>
    $('.marquee').marquee({
        pauseOnHover: true,
        duration: 10000
    });
</script>
</body>
</html>