<?php

require 'vendor/autoload.php'; // Ajuste o caminho se necessário

use PixApiBB\API\API;
use PixApiBB\Services\Cobranca\Cobranca;
use PixApiBB\Services\Cobranca\Exceptions\ForbbidenException;
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

  $result = $pixCobranca->revisarComTxId(
    "8JQC71nlw2WLASmqTz9ep4zheZ",
    null,
    'Bruno Test 2',
    "12345678909",
    "52345679000195",
    200,
    "Cobrança dos serviços prestados XYZ.",
    Cobranca::ATIVA
  );
  
  var_dump($result);

} catch(ForbbidenException $e) {
  var_dump("Cobrança não encontrada.", $e->getMessage());
} catch(RequisicaoFormatoInvalidoException $e) {
  var_dump("Requisição com formato inválido.", $e->getMessage());
} catch(UnauthorizedException $e) {
  var_dump("Requisição de participante autenticado que viola alguma regra de autorização.", $e->getMessage());
} catch(ServicoIndisponivelException $e) {
  var_dump("Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.", $e->getMessage());
} catch(Exception $e) {
  var_dump("EXCEPT: ", $e->getMessage());
} 
