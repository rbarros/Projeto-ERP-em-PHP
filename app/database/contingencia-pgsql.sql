CREATE TABLE motivo_contingencia( 
      id  SERIAL    NOT NULL  , 
      motivo varchar  (500)   NOT NULL  , 
      reemissao boolean   NOT NULL    DEFAULT '0', 
      tipo varchar  (10)   NOT NULL    DEFAULT 'venda', 
      tratamento varchar  (300)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE retorno_error( 
      id  SERIAL    NOT NULL  , 
      id_retorno varchar  (20)   NOT NULL  , 
      id_empresa varchar  (60)   NOT NULL  , 
      motivo integer   NOT NULL  , 
      data_retorno timestamp   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas integer   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendaError( 
      id  SERIAL    NOT NULL  , 
      id_venda integer   NOT NULL  , 
      n_venda varchar  (20)   NOT NULL  , 
      motivo integer   NOT NULL  , 
      data_venda timestamp   , 
      status varchar  (20)   NOT NULL    DEFAULT 'novo', 
      tentativas integer   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE retorno_error ADD CONSTRAINT fk_retorno_error_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 
ALTER TABLE vendaError ADD CONSTRAINT fk_vendaError_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 

  
