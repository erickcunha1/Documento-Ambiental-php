<?php
    // Função para extrair as datas
    function extrairData($texto) {
        // Define o padrão de expressão regular para encontrar datas após "IMAGEM DEPOIS"
        $padrao = '/IMAGEM DEPOIS\s*(\d{2}\/\d{2}\/\d{4})/';
        
        // Realiza a correspondência da expressão regular no texto
        preg_match_all($padrao, $texto, $matches);
        
        // Retorna as datas extraídas (primeiro grupo de captura)
        return isset($matches[1]) ? $matches[1] : [];
    }

    function calcularAnosMeses($dataExtraida) {
        // Define o formato esperado da data
        $formato = 'd/m/Y';
    
        // Converte a string de entrada em um objeto DateTime, usando o formato correto
        $dataExtraida = DateTime::createFromFormat($formato, $dataExtraida);
    
        // Verifica se a data foi criada com sucesso
        if (!$dataExtraida) {
            throw new Exception("Data inválida ou no formato incorreto: $dataExtraida");
        }
    
        // Obtém a data atual
        $dataAtual = new DateTime();
    
        // Calcula a diferença entre as datas
        $diferenca = $dataExtraida->diff($dataAtual);
    
        // Obtém anos, meses e dias da diferença
        $anosDiferenca = $diferenca->y;
        $mesesDiferenca = $diferenca->m;
        $diasDiferenca = $diferenca->d;
    
        // Converte os meses e dias em fração de ano
        $diasNoMesAnterior = (int) $dataAtual->format('t'); // Número de dias no mês atual
        $mesesDiferenca += $diasDiferenca / $diasNoMesAnterior;
    
        // Calcula o valor decimal final
        $valorDecimal = $anosDiferenca + ($mesesDiferenca / 12);
    
        // Arredonda para 2 casas decimais e retorna
        return round($valorDecimal, 2);
    }

    // Exemplo de uso
    // $dataExtraida = "2022-05-15"; 
    // echo calcularAnosMeses($dataExtraida);
?>
