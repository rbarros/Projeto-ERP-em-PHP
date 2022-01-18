CREATE TABLE nfce( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `ambienteEmissao` varchar  (30)   , 
      `informacoesAdicionais` varchar  (200)   , 
      `presencaConsumidor` varchar  (20)   , 
      `numVenda` varchar  (30)   NOT NULL  , 
      `status` varchar  (50)   , 
      `n_nfce` int   , 
      `link_cupom` varchar  (1000)   , 
      `id_loja` int   , 
      `retorno_nfce` int   , 
      `venda_id` int   , 
      `dt_nfce` date   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nfce_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cfop` int   , 
      `codigo` int   , 
      `descricao` varchar  (200)   , 
      `ncm` int   , 
      `cest` int   , 
      `quantidade` int   , 
      `unidadeMedida` varchar  (10)   , 
      `valorUnitario` double   , 
      `percentual` double   , 
      `situacaoTributaria` int   , 
      `origem` int   , 
      `nfce_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nfce_pagamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `prazo` varchar  (80)   , 
      `pedido_id` int   , 
      `nfce_id` int   NOT NULL  , 
      `tipo` varchar  (100)   , 
      `valor` double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE venda( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `n_venda` varchar  (30)   NOT NULL  , 
      `id_interno` varchar  (20)   NOT NULL  , 
      `cliente_id` int   , 
      `status` varchar  (50)   , 
      `vendedor_id` int   , 
      `estado_venda_id` int   , 
      `system_unit_id` int   , 
      `dt_venda` datetime   NOT NULL  , 
      `obs` varchar  (400)   , 
      `valor_total` double   , 
      `total_desconto` double   , 
      `loja` int   NOT NULL  , 
      `id_venda` int   , 
      `variavel_duplicidade` varchar  (600)   , 
      `forma_pagamento` varchar  (30)   , 
      `caixa` varchar  (30)   , 
      `func_caixa` varchar  (50)   , 
      `fiscal` varchar  (1)   , 
      `total_produtos` double   , 
      `total_pagamentos` double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE venda_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `deposito` int   , 
      `name` varchar  (200)   , 
      `venda_id` int   NOT NULL  , 
      `quantidade` int   NOT NULL  , 
      `valor_unitario` double   NOT NULL  , 
      `valor_desconto` double   , 
      `valor_total` double   , 
      `SKU` varchar  (50)   , 
      `loja_id` int   , 
      `dt_venda` date   NOT NULL  , 
      `cest` varchar  (30)   , 
      `ncm` varchar  (30)   , 
      `cfop` int   , 
      `percentual` int   , 
      `unidadeMedida` varchar  (4)     DEFAULT 'UN', 
      `situacaoTributaria` int   , 
      `origem` int   , 
      `categoria_produto` int   , 
      `fornecedor` int   , 
      `referencia` varchar  (30)   , 
      `marca` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE venda_pagamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `metodo_pgto` varchar  (100)   NOT NULL  , 
      `valor_pgto` double   NOT NULL  , 
      `venda_id` int   NOT NULL  , 
      `dt_venda` date   NOT NULL  , 
      `id_loja` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
 ALTER TABLE venda ADD UNIQUE (id_interno);
  
 ALTER TABLE nfce_item ADD CONSTRAINT fk_itens_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE nfce_pagamento ADD CONSTRAINT fk_formas_pgto_nfce FOREIGN KEY (nfce_id) references nfce(id); 
ALTER TABLE venda_item ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references venda(id); 
ALTER TABLE venda_pagamento ADD CONSTRAINT fk_pagamentos_venda FOREIGN KEY (venda_id) references venda(id); 

  
