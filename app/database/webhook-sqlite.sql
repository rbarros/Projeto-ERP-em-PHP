PRAGMA foreign_keys=OFF; 

CREATE TABLE nfce_retorno( 
      id  INTEGER    NOT NULL  , 
      id_externo varchar  (20)   NOT NULL  , 
      tipo varchar  (30)   , 
      status varchar  (50)   , 
      motivoStatus varchar  (200)   , 
      ambienteEmissao varchar  (20)   , 
      enviadaPorEmail varchar  (30)   , 
      dataCriacao date   , 
      dataUltimaAlteracao date   , 
      forcarEmissaoContingencia text   , 
      numero int   , 
      serie int   , 
      dataEmissao date   , 
      chaveAcesso varchar  (200)   , 
      dataAutorizacao date   , 
      linkDanfe varchar  (200)   , 
      linkDownloadXml varchar  (200)   , 
      lnkConsultaPorChaveAcesso varchar  (200)   , 
      emitidaEmContingencia text   , 
      empresaId varchar  (50)   , 
      numeroProtocolo varchar  (100)   , 
      digestValue varchar  (30)   , 
      valorTotal double   , 
      informacoesAdicionais varchar  (400)   , 
      qrCode varchar  (400)   , 
 PRIMARY KEY (id)) ; 

 
 CREATE UNIQUE INDEX unique_idx_nfce_retorno_id_externo ON nfce_retorno(id_externo);
 
  
