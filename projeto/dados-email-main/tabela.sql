CREATE TABLE `dados_email` (
  `arquivo` varchar(255) NOT NULL,
  `remetente` varchar(200) NOT NULL,
  `destinatario` varchar(200) NOT NULL,
  `data_hora` varchar(200) NOT NULL,
  `conteudo` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;