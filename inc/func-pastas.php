<?php
function abrirPasta()
{
    // Verifica se os arquivos foram enviados
    if (!isset($_FILES['pasta'])) {
        return "<p>Nenhuma pasta foi selecionada.</p>";
    }

    $arquivos = $_FILES['pasta']['name'];

    // Monta a saída em HTML
    $saida = "<h2>Conteúdo da pasta selecionada:</h2>";
    $saida .= "<ul>";
    foreach ($arquivos as $arquivo) {
        $saida .= "<li>$arquivo</li>";
    }
    $saida .= "</ul>";

    return $saida;
}
?>
