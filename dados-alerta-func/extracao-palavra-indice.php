<?php
// Função para obter a palavra após uma referência em um array de palavras
    function obterPalavraPorIndice($palavras, $palavraReferencia, $indice) {
        // Verifica se o array não está vazio
        if (!empty($palavras)) {
            // Procura a palavra de referência no array
            $posicaoReferencia = array_search($palavraReferencia, $palavras);

            // Verifica se a palavra de referência foi encontrada
            if ($posicaoReferencia !== false) {
                // Calcula a posição da palavra desejada com base no índice
                $indicePalavra = $posicaoReferencia + $indice;

                // Verifica se o índice está dentro dos limites do array de palavras
                if (isset($palavras[$indicePalavra])) {
                    return $palavras[$indicePalavra];
                } else {
                    return "Índice fora do alcance.";
                }
            } else {
                return "Palavra de referência não encontrada.";
            }
        } else {
            return "O array está vazio.";
        }
    }

?>