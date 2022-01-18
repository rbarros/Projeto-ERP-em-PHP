PRAGMA foreign_keys=OFF; 

CREATE TABLE motivo_contingencia( 
      id  INTEGER    NOT NULL  , 
      motivo varchar  (500)   NOT NULL  , 
      reemissao text   NOT NULL    DEFAULT '0', 
      tipo varchar  (10)   NOT NULL    DEFAULT 'venda', 
      tratamento varchar  (300)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE retorno_error( 
      id  INTEGER    NOT NULL  , 
      id_retorno varchar  (20)   NOT NULL  , 
      id_empresa varchar  (60)   NOT NULL  , 
      motivo int   NOT NULL  , 
      data_retorno datetime   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id),
FOREIGN KEY(motivo) REFERENCES motivo_contingencia(id)) ; 

CREATE TABLE vendaError( 
      id  INTEGER    NOT NULL  , 
      id_venda int   NOT NULL  , 
      n_venda varchar  (20)   NOT NULL  , 
      motivo int   NOT NULL  , 
      data_venda datetime   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id),
FOREIGN KEY(motivo) REFERENCES motivo_contingencia(id)) ; 

 
 
  
