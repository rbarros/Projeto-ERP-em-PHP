<?php
//lib
use Automattic\WooCommerce\Client;
use eNotasGW\Api\Exceptions as Exceptions;
use eNotasGW\Api\fileParameter as fileParameter;
use TelegramBot\Api\BotApi;
//const


class ApiManager
{
    const DATABASE          = 'base_banco';
    const API_KEY_ENOTAS    = '[API ENOTAS]';
    const AMBIENTE_SERVIDOR = true;
    
    public static function ambienteServidor(){
        return static::AMBIENTE_SERVIDOR;
    }
    
    
//OBTEM AS CREDENCIAIS DO ARNALDO ROBOTICO NAS APLICAÇÕES GOOGLE ------------------------------------------------------------------------------------------------------------------------------
    public static function getGoogleClient()
    {
        $string = 
  '{
  [CREDENCIAIS DO BOT DO GOOGLE SHEETS]
}';
    json_decode($string);
    $fp = fopen('credential.json', 'w');
    //salva a credencial no diretorio atual
    fwrite($fp, $string);
    fclose($fp);
    //configurando o cliente para o sheets.
    $cliente = new \Google_Client();
    $cliente -> setApplicationName('Cadastro Produtos');
    $cliente ->setScopes([\Google_Service_Sheets::SPREADSHEETS]); 
    $cliente -> setAuthConfig('credential.json');
    $cliente -> setAccessType('offline');
    return $cliente;
    }
    
    
    
//OBTEM O CLIENT GOOGLE DRIVE ---------------------------------------------------------------------------------------------------------------------------------------------------------------- 
     public static function sendGoogleFile($param)
    {
        $extensao       = '';
        $user           = isset($param['user'])         ?$param['user']:'desconhecido';
        if(isset($param['file_path'])){
            $file_path  = json_decode(urldecode($param['file_path']))->fileName;
        }else{
            throw new Exception('Parametro sem o caminho do arquivo para envio!');
        }
        $vencimento     = isset($param['vencimento'])   ?str_replace('/','-',$param['vencimento']):date('Y-m-d');
        $fornecedor     = isset($param['fornecedor'])   ?$param['fornecedor']:null;
        $valor          = isset($param['valor'])        ?$param['valor']:null;
        $loja           = isset($param['loja'])         ?$param['loja']:null;
        $Gpasta         = isset($param['Gpasta'])       ?$param['Gpasta']:"[id pasta google drive]";
        $valor          = 'R$ '.str_replace('.',',',$valor);
        
        $tamanho_patch  = strlen($file_path)-3;
        $new_path       = "$vencimento - $loja - $fornecedor - $valor";
        include '/var/www/html/api-google/vendor/autoload.php';
        putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->SetScopes(
        ['https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
        'https://www.googleapis.com/auth/drive.metadata'
        ]);
        try{
            $service = new Google_Service_Drive($client);
        
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($new_path); 
            $file->setParents(array($Gpasta));
            $file->setDescription("Arquivo carregado pelo usuário $user");
            $file->setMimeType("image/$extensao");
        
            $result = $service->files->create(
                $file,
                array(
                    'data' => file_get_contents($file_path),
                    'mimeType' => "image/",
                    'uploadType' => 'media'
                )
            );
            return 'https://drive.google.com/open?id='.$result->id;
            
            //return '<a href="https://drive.google.com/open?id='.$result->id.'" target=" blank">'.$result->name.'</a>';
        }catch(Google_Service_Exception $gs){
            $message = json_decode($gs->getMessage());
            return $message;
        }catch(Exception $e){
            return $e;
        }
    }

 //OBTEM AS CREDENCIAIS PARA O PDV WORDPRESS  DE ACORDO COM O PARAMETRO (PRODUÇÃO OU PROMOÇÃO) -------------------------------------------------------------------------------------------------------
    public static function getWooClient($pdv = null)    {
         switch($pdv){
             case 1:
                    $woocommerce = new Client(
                            $url = 'LINK DO PDV 1', 
                            $ck = 'CONSUMER KEY DO WOO COMMERCE', 
                            $cs = 'CONSUMER SEGURITY WOO COMMERCE',
                                [
                                    'version' => 'wc/v3',
                                ]
                            );  
                    return $woocommerce;
            break;
            default:
                $woocommerce = new Client(
                        $url = 'LINK DO PDV 2', 
                        $ck = 'CONSUMER KEY DO WOO COMMERCE', 
                        $cs = 'CONSUMER SEGURITY WOO COMMERCE',
                            [
                                'version' => 'wc/v3',
                            ]
                        );  
                return $woocommerce;
         }
    }
//REALIZA O GERENCIAMENTO DE MENSSAGENS NO TELEGRAM  -----------------------------------------------------------------------------------------------------------------------------------------
    function sendMessage( $message,$chat=1) {
        try{//define destinatário
            //os dados de key desta api é salva no banco, pois podese cadastrar novos grupos no telegram para diferentes finalidades
            TTransaction::open(static::DATABASE);
            $bot        = new Chatapi($chat);
            TTransaction::close(); 
            $bot_token;
            $chat_id;
            
            $bot_token  = $bot->bot_token;
            $chat_id    = $bot->chat_id;
            $message    = static::AMBIENTE_SERVIDOR ? $message : "--- SERVIDOR TESTE --- \n".$message;
            
            //envio da mensssagem
            $url = "https://api.telegram.org/bot" . $bot_token . "/sendMessage?chat_id=" . $chat_id;
            $url = $url . "&text=" . urlencode($message);
            $ch = curl_init();
            $optArray = array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true
            );
            curl_setopt_array($ch, $optArray);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    catch(Exception $e){
            return $e->getMessage();   
        }
    }
//REALIZA O GERENCIAMENTO DA API DO E-NOTAS -----------------------------------------------------------------------------------------------------------------------------------------------------
    //emitir nfce
    function sendNfce($id_loja_enotas, $nfce_request){
        $apiKey = static::API_KEY_ENOTAS;
       // require('../../src/eNotasGW.php');
        require_once '/var/www/html/vendor/enotas/php-client-v2/src/eNotasGW.php';
        eNotasGW::configure(array(
        	'apiKey' => $apiKey
            ));
        $return = eNotasGW::$NFeConsumidorApi->emitir($id_loja_enotas,$nfce_request);
        return $return;
    }   
    
    //salvar loja
    function sendStore($loja){
        require '/var/www/html/vendor/enotas/php-client-v2/src/eNotasGW.php';
        $result = eNotasGW::$EmpresaApi->inserirAtualizar($loja);
        return $result;
    }
    function updateCertificated($id_loja_enotas, $file, $password){
        require '/var/www/html/vendor/enotas/php-client-v2/src/eNotasGW.php';
        $result = eNotasGW::$EmpresaApi->atualizarCertificado($object->idEmpresa, $arquivoPfxOuP12, $senhaDoArquivo);
        return $result;
    }
    //dados da NFCE, retornar daqui e não direto da classe
    
    
    
    function getNfceConfig(){
        //Definir aqui as variaveis da NFCE:
        $ambiente_emissao                   = static::AMBIENTE_SERVIDOR?'Producao':'Homologacao';
        $presenca_consumidor                = 'OperacaoPresencial';
        $informacoes_adicionais             = 'Documento emitido por ME ou EPP optante pelo Simples Nacional.';
        $config                             = array();
        $config['ambiente_emissao']         = $ambiente_emissao;
        $config['presenca_consumidor']      = $presenca_consumidor;
        $config['informacoes_adicionais']   = $informacoes_adicionais;
        return $config;
    }
    
    
    
    public static function sendMessage2($message,$id){
        
        $chatApi        = null;
        if(isset($id)){
            TTransaction::open(static::DATABASE);
            $chatApi    = new Chatapi($id);
            TTransaction::close();
        }else{
            $chatApi    = new ChatApi(1);//Dev
        }
        $bot = new BotApi($chatApi->bot_token);
        return $bot->sendMessage($chatApi->chat_id,$message);
        }
    
    
    
    
    
    
}
