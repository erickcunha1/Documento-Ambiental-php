<?php
// Função para extrair texto de arquivos PDF

include 'vendor/autoload.php'; // Inclua o autoloader do Composer, caso tenha usado o Composer para instalar a dependência
    use Smalot\PdfParser\Parser;

    function extrairTextoPDF($arquivos) {
        $parser = new Parser();
        $resultado = [];

        // Itera sobre os arquivos enviados
        foreach ($arquivos['name'] as $index => $arquivo) {
            $caminhoArquivo = $arquivos['tmp_name'][$index];

            // Verifica se o arquivo é um PDF
            if (pathinfo($arquivo, PATHINFO_EXTENSION) === 'pdf') {
                try {
                    $pdf = $parser->parseFile($caminhoArquivo);  // Faz o parsing do PDF
                    $texto = $pdf->getText();  // Obtém o texto do PDF
                    $resultado[] = [
                        'arquivo' => htmlspecialchars($arquivo),
                        'texto' => $texto
                    ];
                } catch (Exception $e) {
                    $resultado[] = [
                        'arquivo' => htmlspecialchars($arquivo),
                        'erro' => 'Erro ao extrair texto: ' . $e->getMessage()
                    ];
                }
            } else {
                $resultado[] = [
                    'arquivo' => htmlspecialchars($arquivo),
                    'erro' => 'O arquivo não é um PDF.'
                ];
            }
        }

        return $resultado;
    }
?>