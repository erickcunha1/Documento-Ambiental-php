<?php

// Função para remover o sufixo 'ha' e converter o valor para float
function remover_sufixo_ha($valor_str) {
    // Remove tudo o que não for número, ponto ou vírgula
    $valor_limpo = preg_replace('/[^\d.,]/', '', $valor_str);
    
    // Substitui vírgula por ponto para garantir a conversão correta para float
    return (float) str_replace(',', '.', $valor_limpo);
}

// Função para calcular o VETP1
function calcular_VETP1($VET1, $i, $n1, $p) {
    if (!is_numeric($VET1) || !is_numeric($i) || !is_numeric($n1) || !is_numeric($p)) {
        throw new Exception("Os valores de entrada não são numéricos.");
    }

    return $VET1 * (((1 + $i) ** $n1 - 1) / (2 * $i)) * ($n1 / $p);
}

// Função para calcular o VETP2
function calcular_VETP2($VET2, $i, $n2, $p) {
    if (!is_numeric($VET2) || !is_numeric($i) || !is_numeric($n2) || !is_numeric($p)) {
        throw new Exception("Os valores de entrada não são numéricos.");
    }

    return $VET2 * (((1 + $i) ** $n2 - 1) / (2 * $i * (1 + $i) ** $n2)) * ($n2 / $p);
}

// Função para calcular o VETP total
function calcular_VETP_total($VETP1, $VETP2) {
    return $VETP1 + $VETP2;
}

// Função para calcular o valor do dano reversível
function calcular_valor_dano_reversivel($A, $VETP_reais) {
    if (is_string($A)) {
        // Se A for string, remove o sufixo e converte para float
        try {
            $A = remover_sufixo_ha($A);
        } catch (Exception $e) {
            $A = 0;
        }
    }

    if (!is_numeric($A) || !is_numeric($VETP_reais)) {
        throw new Exception("A ou VETP_reais não são numéricos.");
    }

    $valor_dano = $A * $VETP_reais;
    return number_format($valor_dano, 2, ',', '.');
}

// Função para ler os dados do arquivo Excel (utiliza PHPExcel ou PHPSpreadsheet)
function extrair_valor_data($bioma, $ano) {
    $arquivo_excel = 'complementares/valores_2.xlsx';

    if (!file_exists($arquivo_excel)) {
        echo "Arquivo não encontrado!";
        return null;
    }

    require_once 'vendor/autoload.php'; // Certifique-se de instalar o PHPSpreadsheet

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($arquivo_excel);
    $sheet = $spreadsheet->getActiveSheet();
    $tabela = $sheet->toArray();

    foreach ($tabela as $linha) {
        if ($linha[0] == $ano) {
            // Assumindo que os biomas estão nas colunas a partir da segunda coluna
            $coluna = array_search($bioma, $linha);
            if ($coluna !== false) {
                return (float) $linha[$coluna];
            }
        }
    }

    return null;
}

// Função principal para realizar o cálculo do dano reversível
function realizar_calculo($bioma, $area_afetada, $tempo_n1, $ano) {
    $biomas = [
        'Cerrado' => [10, 20], // Exemplo de dados de bioma
        'Mata Atlântica' => [15, 25],
    ];

    list($n2, $p) = $biomas[$bioma];

    $ano_atual = date('Y');
    $VET1 = extrair_valor_data($bioma, $ano);
    $VET2 = extrair_valor_data($bioma, $ano_atual);
    $i = 0.12; // Taxa de juros (12% ao ano)

    if ($VET1 === null || $VET2 === null) {
        throw new Exception("Não foi possível extrair valores para o bioma {$bioma} nos anos {$ano} ou {$ano_atual}.");
    }

    // Cálculos intermediários
    $VETP1 = calcular_VETP1($VET1, $i, $tempo_n1, $p);
    $VETP2 = calcular_VETP2($VET2, $i, $n2, $p);

    // Cálculo do VETP total
    $VETP = calcular_VETP_total($VETP1, $VETP2);

    // Cálculo final do valor do dano reversível
    return calcular_valor_dano_reversivel($area_afetada, $VETP);
}

try {
    $resultado = realizar_calculo('Cerrado', 100, 5, 2015);
    echo "Valor do Dano Reversível: R$ " . $resultado . "\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
