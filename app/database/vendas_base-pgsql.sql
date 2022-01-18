CREATE TABLE nfce( 
      id  SERIAL    NOT NULL  , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)   NOT NULL  , 
      status varchar  (50)   , 
      n_nfce integer   , 
      link_cupom varchar  (1000)   , 
      id_loja integer   , 
      retorno_nfce integer   , 
      venda_id integer   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_item( 
      id  SERIAL    NOT NULL  , 
      cfop integer   , 
      codigo integer   , 
      descricao varchar  (200)   , 
      ncm integer   , 
      cest integer   , 
      quantidade integer   , 
      unidadeMedida varchar  (10)   , 
      valorUnitario float   , 
      percentual float   , 
      situacaoTributaria integer   , 
      origem integer   , 
      nfce_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_pagamento( 
      id  SERIAL    NOT NULL  , 
      prazo varchar  (80)   , 
      pedido_id integer   , 
      nfce_id integer   NOT NULL  , 
      tipo varchar  (100)   , 
      valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda( 
      id  SERIAL    NOT NULL  , 
      n_venda varchar  (30)   NOT NULL  , 
      id_interno varchar  (20)   NOT NULL  , 
      cliente_id integer   , 
      status varchar  (50)   , 
      vendedor_id integer   , 
      estado_venda_id integer   , 
      system_unit_id integer   , 
      dt_venda timestamp   NOT NULL  , 
      obs varchar  (400)   , 
      valor_total float   , 
      total_desconto float   , 
      loja integer   NOT NULL  , 
      id_venda integer   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos float   , 
      total_pagamentos float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item( 
      id  SERIAL    NOT NULL  , 
      produto_id integer   NOT NULL  , 
      deposito integer   , 
      name varchar  (200)   , 
      venda_id integer   NOT NULL  , 
      quantidade integer   NOT NULL  , 
      valor_unitario float   NOT NULL  , 
      valor_desconto float   , 
      valor_total float   , 
      SKU varchar  (50)   , 
      loja_id integer   , 
      dt_venda date   NOT NULL  , 
      cest varchar  (30)   , 
      ncm varchar  (30)   , 
      cfop integer   , 
      percentual integer   , 
      unidadeMedida varchar  (4)     DEFAULT 'UN', 
      situacaoTributaria integer   , 
      origem integer   , 
      categoria_produto integer   , 
      fornecedor integer   , 
      referencia varchar  (30)   , 
      marca integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_pagamento( 
      id  SERIAL    NOT NULL  , 
      metodo_pgto varchar  (100)   NOT NULL  , 
      valor_pgto float   NOT NULL  , 
      venda_id integer   NOT NULL  , 
      dt_venda date   NOT NULL  , 
      id_loja integer   , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE venda ADD UNIQUE (id_interno);
  
 ALTER TABLE nfce_item ADD CONSTRAINT fk_itens_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE nfce_pagamento ADD CONSTRAINT fk_formas_pgto_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE venda_item ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references venda(id); 
ALTER TABLE venda_pagamento ADD CONSTRAINT fk_pagamentos_venda FOREIGN KEY (venda_id) references venda(id); 

  
