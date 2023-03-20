<?php


function getTemplate($pedido) {
    return <<<HTML

    <html>
    <body>
        <h1>Se ha generado el pedido {$pedido}, puedes verlo en la web para facturar.</h1>
    </body>
    </html>

    HTML;
}

?>