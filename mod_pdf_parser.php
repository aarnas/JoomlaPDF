<?php
defined('_JEXEC') or die('Restricted access');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <button onclick="PopupCenter(href='modules/mod_pdf_parser/pdf_html.php','PDF => HTML',600,300)">PDF to HTML</button>
    <button onclick="PopupCenter(href='modules/mod_pdf_parser/editor.php','Editor',1000,800)">Edit HTML</button>

    <script>
    function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var systemZoom = width / window.screen.availWidth;
    var left = (width - w) / 2 / systemZoom + dualScreenLeft
    var top = (height - h) / 2 / systemZoom + dualScreenTop
    var newWindow = window.open(url, title, 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) newWindow.focus();
    }
    </script>
</head>
<body>
</body>
</html>