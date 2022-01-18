CREATE TABLE curvaABC( 
      id number(10)    NOT NULL , 
      nome_produto varchar  (100)    NOT NULL , 
      sku varchar  (30)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      deposito_id number(10)   , 
      curva varchar  (1)    NOT NULL , 
      dtAtualizacao date   , 
      porcentagem binary_double   , 
      valor binary_double   , 
      posicao number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoDiario( 
      id number(10)    NOT NULL , 
      nome_produto varchar  (100)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      semanal number(10)   , 
      valor_semanal binary_double   , 
      quinzenal number(10)   , 
      valor_quinzenal binary_double   , 
      mensal number(10)   , 
      valor_mensal binary_double   , 
      dtAtualizacao date   , 
      loja_id number(10)    NOT NULL , 
      estoque_id number(10)   , 
      deposito_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoMensal( 
      id number(10)    NOT NULL , 
      nome_produto varchar  (100)    NOT NULL , 
      quantidade number(10)    NOT NULL , 
      valor binary_double    NOT NULL , 
      mes number(10)  (12)    NOT NULL , 
      ano number(10)    NOT NULL , 
      dtAtualizacao date   , 
      loja_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      estoque_id number(10)   , 
      deposito_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

 
  CREATE SEQUENCE curvaABC_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER curvaABC_id_seq_tr 

BEFORE INSERT ON curvaABC FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT curvaABC_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produtoDiario_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produtoDiario_id_seq_tr 

BEFORE INSERT ON produtoDiario FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produtoDiario_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produtoMensal_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produtoMensal_id_seq_tr 

BEFORE INSERT ON produtoMensal FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produtoMensal_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
