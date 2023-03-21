<?php

  
$caminho = './mensagens';

$_arquivo = '';
$_remetente = '';
$_destinatario = '';
$_dataHora = '';
$_conteudo = '';



function insertDB($arquivo, $remetente, $destinatario, $data_hora, $conteudo) {

    $servername = "127.0.0.1";
    $username = "root";
    $password = "password";
    $dbname = "dados";

    // Conectando...
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checando conexao...
    if ($conn->connect_error) {
        die("Conexao falhou: " . $conn->connect_error);
    }

    $insertSQL = "INSERT INTO dados_email (arquivo, remetente, destinatario, data_hora, conteudo)
    VALUES ('".$arquivo."', '".$remetente."', '".$destinatario."','".$data_hora."','".$conteudo."')";

    if ($conn->query($insertSQL) === TRUE) {
        echo "Registro criado";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
    
    $conn->close();

}



$dir = opendir($caminho);
while ($file = readdir($dir)) {
    if ($file == '.' || $file == '..') {
        continue;
    }

    $f = fopen($caminho . '/' . $file, 'r');
    $linha = 0;

    while(!feof($f)) {
        $linha++;
        $conteudoLinha =  fgets($f);
        // linha "De:"
        if ($linha == 1){
            $partes = explode("\t", $conteudoLinha);
            // checa se o arquivo é de email
            if (stristr($partes[0], "De:")) {
                $pedaco = preg_match("/([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}/i", $partes[1]);
                // echo $partes[1] . PHP_EOL;
                $_arquivo = $file;
                $_remetente =  $partes[1];
            }
        }

        // linha "Enviado:"
        if ($linha == 2) {
            $partes = explode("\t", $conteudoLinha);
            if (stristr($partes[0], "Enviado em:")) {
                // echo $partes[1] . PHP_EOL;
                $_dataHora = $partes[1];
            }
        }
        // linha "Para:"
        if ($linha == 3) {
            $partes = explode("\t", $conteudoLinha);
            if (stristr($partes[0], "Para:")) {
                // echo $partes[1] . PHP_EOL;
                $_destinatario = $partes[1];
            }
        }
        if ($linha > 3) {
            $_conteudo .= $conteudoLinha . PHP_EOL;
        }
    }

    echo "Processando " . $file . PHP_EOL;

    insertDB($_arquivo, $_remetente, $_destinatario, $_data_hora, $_conteudo);

    fclose($f);
 
}

closedir($dir)


?>