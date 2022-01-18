CREATE TABLE motivo_contingencia( 
      id  INT IDENTITY    NOT NULL  , 
      motivo varchar  (500)   NOT NULL  , 
      reemissao bit   NOT NULL    DEFAULT '0', 
      tipo varchar  (10)   NOT NULL    DEFAULT 'venda', 
      tratamento varchar  (300)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE retorno_error( 
      id  INT IDENTITY    NOT NULL  , 
      id_retorno varchar  (20)   NOT NULL  , 
      id_empresa varchar  (60)   NOT NULL  , 
      motivo int   NOT NULL  , 
      data_retorno datetime2   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendaError( 
      id  INT IDENTITY    NOT NULL  , 
      id_venda int   NOT NULL  , 
      n_venda varchar  (20)   NOT NULL  , 
      motivo int   NOT NULL  , 
      data_venda datetime2   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE retorno_error ADD CONSTRAINT fk_retorno_error_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 
ALTER TABLE vendaError ADD CONSTRAINT fk_vendaError_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 

  
