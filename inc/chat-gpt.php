<?php
require 'vendor/autoload.php'; // Para carregar a biblioteca OpenAI
use OpenAI\Client;

function extrairDadosGPT($text, $apiKey) {
    $client = OpenAI::client($apiKey);

    $response = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ["role" => "system", "content" => "Você é um assistente que extrai informações específicas de documentos."],
            ["role" => "user", "content" => "
            Extraia as seguintes informações deste documento e apresente-as no formato de um array PHP: 
                município, área (em hectares), código CAR, longitude e latitude, o valor da reserva legal, 
                CPF/CNPJ e Denominação do imóvel rural, área do imóvel rural. 
                Certifique-se de que os valores estejam corretos e bem formatados 
                (o nome das chaves devem ser: municipio, area, codigo_CAR, latitude, longitude, reserva_legal, cpf, denominacao e total_area):\n\n$text"]
        ]
    ]);

    return $response['choices'][0]['message']['content'];
}

function formatarResposta($response) {
    $response = trim($response);

    if (strpos($response, '```') === 0 && substr($response, -3) === '```') {
        $response = substr($response, 3, -3);
    }

    $response = str_replace(["\n", "\r"], '', $response);
    return $response;
}

function substituirDicionario($cleanedDataStr, $pdfText, $apiKey) {
    while (true) {
        try {
            $extractedData = eval('return ' . $cleanedDataStr . ';');

            if (is_array($extractedData)) {
                return $extractedData;
            }
        } catch (Throwable $e) {
            $cleanedDataStr = extrairDadosGPT($pdfText, $apiKey);
        }
    }
}
$substituicoes = [
    "$proprietario" => null, // Alterado para manter consistência ou inicializar a variável corretamente
    "$municipio" => $extractedData['municipio'] ?? '',
    "$area" => $extractedData['area'] ?? '',
    "$car" => null,
    "$lat" => $extractedData['latitude'] ?? '',
    "$lon" => $extractedData['longitude'] ?? '',
    "$rl" => $extractedData['reserva_legal'] ?? '',
    "$app" => $extractedData['area'] ?? '',
    "$cpf" => null,
    "$compromitente" => $extractedData['proprietario'] ?? '',
    "$denominacao" => $extractedData['denominacao'] ?? '',
    "$total_area" => $extractedData['total_area'] ?? '',
    "$CEFIR" => null,
    "$numero_alerta" => null,
    "$promotoria" => null,
    "$valor" => null,
    "$extenso" => null,
];
function criarDocumento($caminho, $apiKey) {
    // Supondo que exista uma função chamada `extractTextFromPDF` para extrair texto do PDF.
    $pdfText = extractTextFromPDF($caminho);

    $extractedDataStr = extrairDadosGPT($pdfText, $apiKey);
    $cleanedDataStr = formatarResposta($extractedDataStr);
    $extractedData = substituirDicionario($cleanedDataStr, $pdfText, $apiKey);

    
    return $extractedData;
}
    

function extractTextFromPDF($filePath) {
    // Aqui você pode usar uma biblioteca como `smalot/pdfparser` para extrair texto do PDF.
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($filePath);
    return $pdf->getText();
}