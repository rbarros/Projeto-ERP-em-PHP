CREATE TABLE curvaABC( 
      id  SERIAL    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      sku varchar  (30)   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      deposito_id integer   , 
      curva varchar  (1)   NOT NULL  , 
      dtAtualizacao date   , 
      porcentagem float   , 
      valor float   , 
      posicao integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoDiario( 
      id  SERIAL    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      semanal integer   , 
      valor_semanal float   , 
      quinzenal integer   , 
      valor_quinzenal float   , 
      mensal integer   , 
      valor_mensal float   , 
      dtAtualizacao date   , 
      loja_id integer   NOT NULL  , 
      estoque_id integer   , 
      deposito_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoMensal( 
      id  SERIAL    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      quantidade integer   NOT NULL  , 
      valor float   NOT NULL  , 
      mes integer   NOT NULL  , 
      ano integer   NOT NULL  , 
      dtAtualizacao date   , 
      loja_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      estoque_id integer   , 
      deposito_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 
  
