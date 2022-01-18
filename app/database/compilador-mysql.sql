CREATE TABLE curvaABC( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome_produto` varchar  (100)   NOT NULL  , 
      `sku` varchar  (30)   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `deposito_id` int   , 
      `curva` varchar  (1)   NOT NULL  , 
      `dtAtualizacao` date   , 
      `porcentagem` double   , 
      `valor` double   , 
      `posicao` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE produtoDiario( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome_produto` varchar  (100)   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `semanal` int   , 
      `valor_semanal` double   , 
      `quinzenal` int   , 
      `valor_quinzenal` double   , 
      `mensal` int   , 
      `valor_mensal` double   , 
      `dtAtualizacao` date   , 
      `loja_id` int   NOT NULL  , 
      `estoque_id` int   , 
      `deposito_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE produtoMensal( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome_produto` varchar  (100)   NOT NULL  , 
      `quantidade` int   NOT NULL  , 
      `valor` double   NOT NULL  , 
      `mes` int   NOT NULL  , 
      `ano` int   NOT NULL  , 
      `dtAtualizacao` date   , 
      `loja_id` int   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `estoque_id` int   , 
      `deposito_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
 
  
