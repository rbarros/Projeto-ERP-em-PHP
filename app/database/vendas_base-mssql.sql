CREATE TABLE nfce( 
      id  INT IDENTITY    NOT NULL  , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)   NOT NULL  , 
      status varchar  (50)   , 
      n_nfce int   , 
      link_cupom varchar  (1000)   , 
      id_loja int   , 
      retorno_nfce int   , 
      venda_id int   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_item( 
      id  INT IDENTITY    NOT NULL  , 
      cfop int   , 
      codigo int   , 
      descricao varchar  (200)   , 
      ncm int   , 
      cest int   , 
      quantidade int   , 
      unidadeMedida varchar  (10)   , 
      valorUnitario float   , 
      percentual float   , 
      situacaoTributaria int   , 
      origem int   , 
      nfce_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_pagamento( 
      id  INT IDENTITY    NOT NULL  , 
      prazo varchar  (80)   , 
      pedido_id int   , 
      nfce_id int   NOT NULL  , 
      tipo varchar  (100)   , 
      valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda( 
      id  INT IDENTITY    NOT NULL  , 
      n_venda varchar  (30)   NOT NULL  , 
      id_interno varchar  (20)   NOT NULL  , 
      cliente_id int   , 
      status varchar  (50)   , 
      vendedor_id int   , 
      estado_venda_id int   , 
      system_unit_id int   , 
      dt_venda datetime2   NOT NULL  , 
      obs varchar  (400)   , 
      valor_total float   , 
      total_desconto float   , 
      loja int   NOT NULL  , 
      id_venda int   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos float   , 
      total_pagamentos float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item( 
      id  INT IDENTITY    NOT NULL  , 
      produto_id int   NOT NULL  , 
      deposito int   , 
      name varchar  (200)   , 
      venda_id int   NOT NULL  , 
      quantidade int   NOT NULL  , 
      valor_unitario float   NOT NULL  , 
      valor_desconto float   , 
      valor_total float   , 
      SKU varchar  (50)   , 
      loja_id int   , 
      dt_venda date   NOT NULL  , 
      cest varchar  (30)   , 
      ncm varchar  (30)   , 
      cfop int   , 
      percentual int   , 
      unidadeMedida varchar  (4)     DEFAULT 'UN', 
      situacaoTributaria int   , 
      origem int   , 
      categoria_produto int   , 
      fornecedor int   , 
      referencia varchar  (30)   , 
      marca int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_pagamento( 
      id  INT IDENTITY    NOT NULL  , 
      metodo_pgto varchar  (100)   NOT NULL  , 
      valor_pgto float   NOT NULL  , 
      venda_id int   NOT NULL  , 
      dt_venda date   NOT NULL  , 
      id_loja int   , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE venda ADD UNIQUE (id_interno);
  
 ALTER TABLE nfce_item ADD CONSTRAINT fk_itens_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE nfce_pagamento ADD CONSTRAINT fk_formas_pgto_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE venda_item ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references venda(id); 
ALTER TABLE venda_pagamento ADD CONSTRAINT fk_pagamentos_venda FOREIGN KEY (venda_id) references venda(id); 

  
