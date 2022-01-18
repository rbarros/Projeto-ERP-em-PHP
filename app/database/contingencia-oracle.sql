CREATE TABLE motivo_contingencia( 
      id number(10)    NOT NULL , 
      motivo varchar  (500)    NOT NULL , 
      reemissao char(1)    DEFAULT '0'  NOT NULL , 
      tipo varchar  (10)    DEFAULT 'venda'  NOT NULL , 
      tratamento varchar  (300)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE retorno_error( 
      id number(10)    NOT NULL , 
      id_retorno varchar  (20)    NOT NULL , 
      id_empresa varchar  (60)    NOT NULL , 
      motivo number(10)    NOT NULL , 
      data_retorno timestamp(0)   , 
      status varchar  (20)    DEFAULT 'novo'  NOT NULL , 
      tentativas number(10)    DEFAULT 0  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendaError( 
      id number(10)    NOT NULL , 
      id_venda number(10)    NOT NULL , 
      n_venda varchar  (20)    NOT NULL , 
      motivo number(10)    NOT NULL , 
      data_venda timestamp(0)   , 
      status varchar  (20)    DEFAULT 'novo'  NOT NULL , 
      tentativas number(10)    DEFAULT 0  NOT NULL , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE retorno_error ADD CONSTRAINT fk_retorno_error_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 
ALTER TABLE vendaError ADD CONSTRAINT fk_vendaError_motivo FOREIGN KEY (motivo) references motivo_contingencia(id); 
 CREATE SEQUENCE motivo_contingencia_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER motivo_contingencia_id_seq_tr 

BEFORE INSERT ON motivo_contingencia FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT motivo_contingencia_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE retorno_error_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER retorno_error_id_seq_tr 

BEFORE INSERT ON retorno_error FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT retorno_error_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE vendaError_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER vendaError_id_seq_tr 

BEFORE INSERT ON vendaError FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT vendaError_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
