<?php

class ClienteService
{
    const DATABASE              = 'base_banco';
    const ACTIVE_RECORD         = 'Pessoa';
    
   public function handle($param)
    {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            unset($param['class']);
            unset($param['method']);
            switch( $method )
        {
            case 'PUT':
                return self::novoClienteManual($param);
                break;
            case 'POST':
                return self::novoClienteWebhook($param);
                break;
            case 'GET':
                return self::novoClienteManual($param,1);
                break;
            default:
                return "metodos indisponiveis";
        }
    }
    //metodos de tratamentos iniciais de clientes LEMBRAR QUE ESSAS FUNçÔES NAO DEVEM RETORNAR NADA AO CONSULTANTE;
    public function novoClienteWebhook($param){
         try{//responsável por converter um e-mail em uma busca e então enviar apra salvar no banco de dados
               $email                               = $param['billing']->email;
               if($param['role']=='customer'){
                   TTransaction::open(static::DATABASE);
                   $pessoas                         = Pessoa::where('email','=',$email)->load();
                   $retorno;
                   $clientes_array              = array();
                   $link_pdv                    = $param['_links']->collection[0]->href;
                   $link                        = str_replace('pdvpromocao','',$link_pdv);
                   $isPromo                     = $link != $link_pdv ?true:false;
                   if(!$isPromo){
                       $clientes_array['PDV1']  = $param;
                       $clientes_array['PDV2']  = self::buscarCliente($email,1);
                       $retorno                 = self::salvarCliente($clientes_array);
                   }else{
                       $clientes_array['PDV2']  = $param;
                       $clientes_array['PDV1']  = self::buscarCliente($email);
                       $retorno                 = self::salvarCliente($clientes_array);
                   }
               }else{
                   $retorno = "este e-mail não é de um cliente cadastrado!";
               }
               TTransaction::close();
               return $retorno;
           }catch(Exception $e){
             return $e->getMessage();
           }
     }
     public function novoClienteManual($param,$consulta = false){
        try{
            $retorno;
            $clientes_array                     = array();
            if(!$consulta){//se é apenas uma consulta no PDV
                $clientes_array['PDV1']     = self::buscarCliente($email);
                $clientes_array['PDV2']     = self::buscarCliente($email,1);
                $retorno;
                if(!$clientes_array['PDV1'] && !$clientes_array['PDV2']){
                   throw new exception ('cliente não encontrado nos PDVs');
               }else{
                   $retorno                 = self::salvarCliente($clientes_array); 
               }
            }else{
                $clientes_array['PDV1']         = self::buscarCliente($email);
                $clientes_array['PDV2']         = self::buscarCliente($email,1);
                $retorno = $clientes_array;
            }
            return $retorno;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function salvarCliente($param){
        //metodo responsável por ajustar os dados e então salvar no banco de dados
       try{
           $documento;
           $clientes;
           $clienteWoo;
           $isCliente;
           $link_pdv;
           $dt_ativacao;
           $id;
           $meta_dados;
           $retorno;
           $cliente;
           $alt             = 1;
           $isSalved        = false;
           for($i=1;$i<3;$i++){
               $pdv                                     = $param["PDV$i"];
               if($pdv){
                   $alt                                 = $i==1?2:1;
                   if(is_array($pdv)){
                      $clienteWoo                       = $pdv['billing']; //renomeia apra cliente
                      $isCliente                        = $pdv['role'] == "customer"?true:false; 
                      $dt_ativacao                      = str_replace("T"," ",$pdv['date_created']);
                      $id                               = $pdv['id'];
                      $meta_dados                       = $pdv['meta_data'];
                   }else{
                      $clienteWoo                       = $pdv->billing;
                      $isCliente                        = $pdv->role == "customer"?true:false;
                      $dt_ativacao                      = str_replace("T"," ",$pdv->date_created);
                      $id                               = $pdv->id;
                      $meta_dados                       = $pdv->meta_data; 
                   }
                   if($isCliente){
                       if($meta_dados[1]->value != null){
                           if(!$isSalved){
                                $documento                  = $meta_dados[1]->value;
                                TTransaction::open(static::DATABASE);
                                $clientes                   = Pessoa::where('email','=',$clienteWoo->email)->load();
                                $cliente                    = $clientes ? $clientes[0] : new Pessoa();
                                $cliente->tipo_pessoa       = 2;
                                $cliente->nome              = strtoupper($clienteWoo->first_name.' '.$clienteWoo->last_name );
                                $cliente->documento         = $documento ;
                                $cliente->obs               = "cliente enviado pelo PDV$i";
                                $cliente->fone              = $clienteWoo->phone;
                                $cliente->email             = $clienteWoo->email;
                                //calcula estado    
                                $estados                    = Estado::where("uf",'=',$clienteWoo->state)->load();
                                if($estados){
                                    $estado                 = $estados[0];
                                    $cliente->estado_id     = $estado->id;
                                }else{
                                    $cliente->estado_id     = null;
                                   // throw new Exception('estado deste cliente não se encontra cadastrado ou está escrito de forma errada');
                                }
                                //calcula cidade
                                $cidadeWoo                  = strtoupper($clienteWoo->city);
                                $cidades                    = Cidade::where('nome','like',"%$cidadeWoo%")->load();
                                if($cidades){
                                    $cidade                 = $cidades[0];
                                    $cliente->cidade_id     = $cidade->id;
                                }else{
                                    $cliente->cidade_id     = null;
                                    //throw new Exception ('cidade deste cliente não se encontra cadastrado ou está escrito de forma errada');
                                }
                                $cliente->endereco          = $clienteWoo->address_1.' '.$clienteWoo->address_2;
                                $cliente->cep               = $clienteWoo->postcode;
                                $cliente->dt_ativacao       = $dt_ativacao;
                                $cliente->dt_desativacao    = null;
                                
                                if($i == 1){
                                    $cliente->id_cliente_pdv1 = $id;
                                }else{
                                    $cliente->id_cliente_pdv2 = $id;
                                }
                                $cliente->store();
                                var_dump($cliente);
                                TTransaction::close();
                                $retorno                    = $cliente;
                                $isSalved                   = true;
                           }
                       }else{
                           ApiManager::sendMessage("Cliente : $clienteWoo->first_name $clienteWoo->last_name \n não cadastrado devido ao CPF/CNPJ ser inválido ou nulo");
                           throw new Exception("cliente sem CPF informado!"); // interrompe a execução
                       }
                }
            }else{
                $alt                                 = $i==1?2:1;
                $pdv                                 = $param["PDV$alt"];
                $retorno;
                if($i==1){
                    $retorno = self::salvarClientePDV($pdv);
                }else{
                    $retorno = self::salvarClientePDV($pdv,1);
                }
                $alt                                 = 'id_cliente_pdv'.$alt;
                $cliente->$alt                       = $retorno->id;
            }
           }//for PDV's
           return $retorno;
       }catch(Exception $e){
         return $e->getMessage();
       }
    }
    
     
     public function buscarCliente($id,$pdv = null){
         try{
             if($pdv != null){//PROMOCAO
                 $woocommerce         = ApiManager::getWooClient($pdv);
                 $params              = array("email" => $id, "role" => 'customer');
                 $clientes            = $woocommerce->get('customers',$params);
                 if($clientes != 'Error: ID inválido do recurso. [woocommerce_rest_invalid_id]'){
                   foreach($clientes as $cliente ){
                       if($cliente->email == $id){
                           return $cliente;
                       }
                   }
                   return false;
                 }
             }else{//PRODUCAO
                 $woocommerce         = ApiManager::getWooClient();
                 $params              = array("email" => $id, "role" => 'customer');
                 $clientes            = $woocommerce->get('customers',$params);
                 if($clientes != 'Error: ID inválido do recurso. [woocommerce_rest_invalid_id]'){
                   foreach($clientes as $cliente ){
                       if($cliente->email == $id){
                           return $cliente;
                       }
                   }
                 return false;
                 }
             }
         }catch(Exception $e){
             return $e->getMessage();
         }
     }
     
     public function salvarClientePDV($customer, $pdv=null){
        try{
            $email;
            $first_name;
            $last_name;
            $username;
            $billing;
            $shipping;
            $meta_data; 
            if(is_array($customer)){
                $email              = $customer['email'];
                $first_name         = $customer['first_name'];
                $last_name          = $customer['last_name'];
                $username           = $customer['username'];
                $billing            = $customer['billing'];
                $shipping           = $customer['shipping'];
                $meta_data          = $customer['meta_data']; 
            }else{
                $email              = $customer->email;
                $first_name         = $customer->first_name;
                $last_name          = $customer->last_name;
                $username           = $customer->username;
                $billing            = $customer->billing;
                $shipping           = $customer->shipping;
                $meta_data          = $customer->meta_data;
            }
           //enviar
             $woocommerce = $pdv != null ? ApiManager::getWooClient($pdv): ApiManager::getWooClient();
               $data = [
                'email'             => $email,
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'username'          => $username,
                'billing'           => [
                    'first_name'    => $billing->first_name,
                    'last_name'     => $billing->last_name,
                    'company'       => $billing->company,
                    'address_1'     => $billing->address_1,
                    'address_2'     => $billing->address_2,
                    'city'          => $billing->city,
                    'state'         => $billing->state,
                    'postcode'      => $billing->postcode,
                    'country'       => $billing->country,
                    'email'         => $billing->email,
                    'phone'         => $billing->phone,
                ],
                'shipping'          => [
                    'first_name'    => $shipping->first_name,
                    'last_name'     => $shipping->last_name,
                    'company'       => $shipping->company,
                    'address_1'     => $shipping->address_1,
                    'address_2'     => $shipping->address_2,
                    'city'          => $shipping->city,
                    'state'         => $shipping->state,
                    'postcode'      => $shipping->postcode,
                    'country'       => $shipping->country
                ],
                'meta_data'         =>[
                    [
                    'key'           => '_billing_vat',
                    'value'         => $meta_data[1]->value
                    ]
                ]
            ]; 
            $retorno =  $woocommerce->post('customers', $data);
            
        }catch(Exception $e){
            return $e->getMessage();
        }
     }
}
