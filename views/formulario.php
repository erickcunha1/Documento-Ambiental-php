<!-- views/formulario.php -->
<?php
    include 'dados-alerta-func/all-functions.php';
    include 'inc/calculos.php'
?>

<h1>Gerenciador de Arquivos</h1>

<form id="form" method="post" enctype="multipart/form-data">
    <label for="pasta">Selecione os arquivos PDF</label>
    <input type="file" id="pasta" name="pasta[]" multiple accept=".pdf">
    
    <button type="submit" name="confirmar">Confirmar</button>
</form>

<div id="resultado">
    <?php
        if (isset($_FILES['pasta'])) {
            $arquivos = $_FILES['pasta'];
            $resultadosTexto = extrairTextoPDF($arquivos);

            // Exibe os resultados
            foreach ($resultadosTexto as $resultado) {
                echo 'Arquivo: ' . htmlspecialchars($resultado['arquivo']) . '<br>';
                
                if (isset($resultado['erro'])) {
                    echo 'Erro: ' . htmlspecialchars($resultado['erro']) . '<br>';
                } else {
                    // Exibe o texto extraído
                    // echo 'Texto extraído:<br>';
                    // Transforma o texto em um array de palavras separadas por quebra de linha
                    $palavras = preg_split('/\s+/', $resultado['texto']);
                    
                    // Exibe a palavra desejada
                    $bioma = obterPalavraPorIndice($palavras, 'BIOMAS', 1);
                    
                    // Exibe as datas extraídas
                    $data = extrairData($resultado['texto']);
                    $dataFormatada = implode(', ', $data);
                    $anoDano = substr($dataFormatada, -4);
                    
                    $valorDataAtual_DataDano = calcularAnosMeses($dataFormatada);;

                    echo realizar_calculo($bioma, 28.88, $valorDataAtual_DataDano, $anoDano) . '<br>';

                }
            }
        }
    ?>
</div>

