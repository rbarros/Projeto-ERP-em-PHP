CREATE TABLE motivo_contingencia( 
      id  integer generated by default as identity     NOT NULL , 
      motivo varchar  (500)    NOT NULL , 
      reemissao char(1)    DEFAULT '0'  NOT NULL , 
      tipo varchar  (10)    DEFAULT 'venda'  NOT NULL , 
      tratamento varchar  (300)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE retorno_error( 
      id  integer generated by default as identity     NOT NULL , 
      id_retorno varchar  (20)    NOT NULL , 
      id_empresa varchar  (60)    NOT NULL , 
      motivo integer    NOT NULL , 
      data_retorno timestamp   , 
      status varchar  (20)    DEFAULT 'novo'  NOT NULL , 
      tentativas integer    DEFAULT 0  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendaError( 
      id  integer generated by default as identity     NOT NULL , 
      id_venda integer    NOT NULL , 
      n_venda varchar  (20)    NOT NULL , 
      motivo integer    NOT NULL , 
      data_venda timestamp   , 
      status varchar  (20)    DEFAULT 'novo'  NOT NULL , 
      tentativas integer    DEFAULT 0  NOT NULL , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE retorno_error ADD CONSTRAINT fk_retorno_error_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 
ALTER TABLE vendaError ADD CONSTRAINT fk_vendaError_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 

  
