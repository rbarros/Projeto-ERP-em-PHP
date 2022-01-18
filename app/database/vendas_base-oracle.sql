CREATE TABLE nfce( 
      id number(10)    NOT NULL , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)    NOT NULL , 
      status varchar  (50)   , 
      n_nfce number(10)   , 
      link_cupom varchar  (1000)   , 
      id_loja number(10)   , 
      retorno_nfce number(10)   , 
      venda_id number(10)   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_item( 
      id number(10)    NOT NULL , 
      cfop number(10)   , 
      codigo number(10)   , 
      descricao varchar  (200)   , 
      ncm number(10)   , 
      cest number(10)   , 
      quantidade number(10)   , 
      unidadeMedida varchar  (10)   , 
      valorUnitario binary_double   , 
      percentual binary_double   , 
      situacaoTributaria number(10)   , 
      origem number(10)   , 
      nfce_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_pagamento( 
      id number(10)    NOT NULL , 
      prazo varchar  (80)   , 
      pedido_id number(10)   , 
      nfce_id number(10)    NOT NULL , 
      tipo varchar  (100)   , 
      valor binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda( 
      id number(10)    NOT NULL , 
      n_venda varchar  (30)    NOT NULL , 
      id_interno varchar  (20)    NOT NULL , 
      cliente_id number(10)   , 
      status varchar  (50)   , 
      vendedor_id number(10)   , 
      estado_venda_id number(10)   , 
      system_unit_id number(10)   , 
      dt_venda timestamp(0)    NOT NULL , 
      obs varchar  (400)   , 
      valor_total binary_double   , 
      total_desconto binary_double   , 
      loja number(10)    NOT NULL , 
      id_venda number(10)   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos binary_double   , 
      total_pagamentos binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item( 
      id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      deposito number(10)   , 
      name varchar  (200)   , 
      venda_id number(10)    NOT NULL , 
      quantidade number(10)    NOT NULL , 
      valor_unitario binary_double    NOT NULL , 
      valor_desconto binary_double   , 
      valor_total binary_double   , 
      SKU varchar  (50)   , 
      loja_id number(10)   , 
      dt_venda date    NOT NULL , 
      cest varchar  (30)   , 
      ncm varchar  (30)   , 
      cfop number(10)   , 
      percentual number(10)   , 
      unidadeMedida varchar  (4)    DEFAULT 'UN' , 
      situacaoTributaria number(10)   , 
      origem number(10)   , 
      categoria_produto number(10)   , 
      fornecedor number(10)   , 
      referencia varchar  (30)   , 
      marca number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_pagamento( 
      id number(10)    NOT NULL , 
      metodo_pgto varchar  (100)    NOT NULL , 
      valor_pgto binary_double    NOT NULL , 
      venda_id number(10)    NOT NULL , 
      dt_venda date    NOT NULL , 
      id_loja number(10)   , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE venda ADD UNIQUE (id_interno);
  
 ALTER TABLE nfce_item ADD CONSTRAINT fk_itens_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE nfce_pagamento ADD CONSTRAINT fk_formas_pgto_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE venda_item ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references venda(id); 
ALTER TABLE venda_pagamento ADD CONSTRAINT fk_pagamentos_venda FOREIGN KEY (venda_id) references venda(id); 
 CREATE SEQUENCE nfce_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nfce_id_seq_tr 

BEFORE INSERT ON nfce FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT nfce_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nfce_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nfce_item_id_seq_tr 

BEFORE INSERT ON nfce_item FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT nfce_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nfce_pagamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nfce_pagamento_id_seq_tr 

BEFORE INSERT ON nfce_pagamento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT nfce_pagamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_id_seq_tr 

BEFORE INSERT ON venda FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT venda_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_item_id_seq_tr 

BEFORE INSERT ON venda_item FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT venda_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_pagamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_pagamento_id_seq_tr 

BEFORE INSERT ON venda_pagamento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT venda_pagamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
