<?php

require 'vendor/autoload.php'; // Ajuste o caminho se necessário

use PixApiBB\API\API;
use PixApiBB\Services\Cobranca\Cobranca;
use PixApiBB\Services\Cobranca\Exceptions\RequisicaoFormatoInvalidoException;
use PixApiBB\Services\Cobranca\Exceptions\ServicoIndisponivelException;
use PixApiBB\Services\Cobranca\Exceptions\UnauthorizedException;

$config = require('config.php');

$api = API::make(
  $config['client_id'],
  $config['client_secret'],
  $config['developer_application_key'],
  $config['api_url'],
  $config['auth_url'],
  $config['debug_mode'],
);

$pixCobranca = Cobranca::make($api);

try {

  $result = $pixCobranca->criar(
    3600,
    "12345678000195",
    '87156053027',
    "Empresa de Serviços SA",
    "37.00",
    "9e881f18-cc66-4fc7-8f2c-a795dbb2bfc1",
    "Serviço realizado.",
    [
      [
        "nome" => "Campo 1",
        "valor" => "Informação Adicional1 do PSP-Recebedor"
      ],
      [
        "nome" => "Campo 2",
        "valor" => "Informação Adicional2 do PSP-Recebedor"
      ]
    ]
  );
  
  var_dump($result);

} catch(RequisicaoFormatoInvalidoException $e) {
  var_dump("Requisição com formato inválido.", $e->getMessage());
} catch(UnauthorizedException $e) {
  var_dump("Requisição de participante autenticado que viola alguma regra de autorização.", $e->getMessage());
} catch(ServicoIndisponivelException $e) {
  var_dump("Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.", $e->getMessage());
} catch(Exception $e) {
  var_dump("EXCEPT: ", $e->getMessage());
} 