import pandas as pd
import sys
import os

# Função para processar dados com Pandas
def extrair_valor_data(bioma, ano):
    # Caminho do arquivo Excel
    arquivo_excel = 'python/tabela_valores.xlsx'
    
    if not os.path.exists(arquivo_excel):
        print(f"Arquivo {arquivo_excel} não encontrado!")
        return None

    tabela = pd.read_excel(arquivo_excel)
    valor = tabela.loc[tabela['Ano'] == ano, bioma].values
    
    if len(valor) > 0:
        try:
            return float(valor[0])  # Certifique-se de retornar um valor numérico
        except ValueError:
            print(f"Valor inválido encontrado para {bioma} no ano {ano}")
            return None
    else:
        print(f"Nenhum valor encontrado para o bioma '{bioma}' no ano {ano}.")
        return None

# Lê os argumentos da linha de comando
if __name__ == "__main__":
    if len(sys.argv) < 3:
        # print("Uso: python extracao_valor.py <bioma> <ano>")
        sys.exit(1)

    bioma = sys.argv[1]
    try:
        ano = int(sys.argv[2])
    except ValueError:
        # print("O argumento <ano> deve ser um número inteiro.")
        sys.exit(1)

    resultado = extrair_valor_data(bioma, ano)
    if resultado is not None:
        print(f"{resultado}")
    else:
        print("Erro ao buscar o valor.")
