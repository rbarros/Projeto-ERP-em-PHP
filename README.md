<div id="top"></div>

<br />
<div align="center">
  <a href="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/blob/master/app/images/icons/erp-logo.png">
    <img src="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/blob/master/app/images/icons/erp-logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Projeto-ERP-em-PHP </h3>

  <p align="center">
    Uma aplicação de ERP para gerenciamento de uma empresa multi-loja.
    <br />
    <a href="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/blob/master/Documenta%C3%A7%C3%A3o/Diagramas/diagramas.md"><strong>Diagramas »</strong></a>
    <br />
    <br />
    <a href="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/blob/master/Documenta%C3%A7%C3%A3o/docs.md">Documentação</a>
    ·
    <a href="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/tree/master/app/images">Imagens</a>
    ·
    <a href="https://github.com/pedrogomes30/Projeto-ERP-em-PHP/issues">Report</a>
  </p>
</div>

<details>
  <summary>Sumário</summary>
  <ol>
    <li>
      <a href="#Sobre-o-projeto">Sobre o pojeto</a>
      <ul>
        <li><a href="#Tecnologias-utilizadas">Tecnologias utilizadas</a></li>
      </ul>
    </li>
    <li>
      <a href="#instalacao">Instalação</a>
      <ul>
        <li><a href="#prerequisitos">Pré-requisitos</a></li>
        <li><a href="#instalacao">Instalação</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
  </ol>
</details>

## Sobre o projeto 

esta é uma versão <b>white label</b>de um projeto deste autor com o objetivo de contruir uma aplicação onde é possivel gerenciar, controlar, e automatizar diversos processos internos de uma empresa multigrupo e multiloja. o sistema é disponibilizado de forma WEB, com a intenção de uso interno.
principais requisitos:
- permitir cadastro de produtos, com integração no PDV utilizado;
- permitir o correto processamento de vendas geradas no PDV, e sua emissão de NFCE;
- permitir elaboração de relatórios;
- permitir o controle e transferência de estoque multigrupo e multiloja;
- permitir o controle de contas a pagar e contas a receber;
- permitir a fácil extração de arquivos XML;
- permitir o controle de funcionários entre lojas;
- permitir o cadastro de clientes;
- entre outros.

## Tecnologias utilizadas

### No projeto
<ul>
  <li><a href="https://www.adiantibuilder.com.br/"> Adianti Biulder</a></li>
  <li><a href="https://www.php.net/"> PHP 7.4</a></li>
  <li><a href="https://www.mysql.com/"> MySql</a></li>
  <li><a href="https://getbootstrap.com/"> Bootstrap</a></li>
  <li><a href="https://www.javascript.com/"> Javascript</a></li>
  <li><a href="https://www.apache.org/"> Apache</a></li>  
</ul>

### API's
<ul>
  <li><a href="https://developer.wordpress.com/docs/"> Wordpress</a></li>
  <li><a href="https://woocommerce.com/documentation/"> WooComerce</a></li>
  <li><a href="https://docs.yithemes.com/"> Yith Pos</a></li>
  <li><a href="https://enotas.com.br/"> E-notas</a></li>
  <li><a href="https://core.telegram.org/"> Telegram</a></li>
  <li><a href="https://cloud.google.com/apis"> Google api</a></li>  
</ul>
##Pre
##Instalação

- Copiar este projeto para a pasta raiz do seu servidor PHP;
- navegar até  o diretório .../app/configs/;
- abrir o arquivo  "application.ini" e gere um seed e uma API key para a aplicação;
- nevegar até a pasta .../app/database e instalar os scripts de banco de dados respectivo (mysql, postgree, sqlite, etc);
- navegar até o arquivo no navegador .../install.php;
- informar as informações do banco;

