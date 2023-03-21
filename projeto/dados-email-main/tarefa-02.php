<?php


    function count_array_values($my_array, $match) 
    { 
        $count = 0; 
        
        foreach ($my_array as $key => $value) 
        { 
            if ($value == $match) 
            { 
                $count++; 
            } 
        } 
        
        return $count; 
    } 

    function porcentagem($valor, $total) {
      return abs( ($total  * $valor) / 100);
    }

    $servername = "127.0.0.1";
    $username = "root";
    $password = "password";
    $dbname = "dados";

    $conteudoCampo = '';

    $arquivoPalavrasChave = file_get_contents('./palavras/palavras.txt', true);
    $conteudoPalavrasArray =  explode(PHP_EOL, $arquivoPalavrasChave);

    // Conectando...
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checando conexao...
    if ($conn->connect_error) {
        die("Conexao falhou: " . $conn->connect_error);
    }

    // Limpando a tabela
    $limpaTabela = "TRUNCATE TABLE palavras";
    $conn->query($limpaTabela);

    // Fazendo a pesquisa
    $insertSQL = "SELECT conteudo FROM dados_email";
    $result = $conn->query($insertSQL);

    $todoConteudo = '';

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $conteudoCampo = $row["conteudo"];
            $todoConteudo .= ' '. $conteudoCampo;
        }
        // Remove palavras duplicadas
        $palavrasConteudo = array_unique(explode(" ", $todoConteudo));
        $porcentagemPalavras = porcentagem(5, count($palavrasConteudo));
        // Retorna a quantidade de ocorrencias 
        $palavrasContador = array_count_values($palavrasConteudo);

        // Checa se a quantidade de palavras é maior que 5% e insere em outra table
        foreach($palavrasContador as $chave => $valor) { 
            if ($chave >= $porcentagemPalavras) {
                $insertPalavra = "INSERT INTO palavras (palavra)
                        VALUES ('".$valor."')";
                $conn->query($insertPalavra);
            }
        }
    }

    $conn->close();

?>