<?php

require 'vendor/autoload.php'; // Ajuste o caminho se necessário

use Carbon\Carbon;
use PixApiBB\API\API;
use PixApiBB\Services\Cobranca\Cobranca;
use PixApiBB\Services\Cobranca\Exceptions\ForbbidenException;
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

$revisao = null;
$txid = "8JQC71nlw2WLASmqTz9ep4zheZ";

$pixCobranca = Cobranca::make($api);

try {

  $result = $pixCobranca->consultarComTxId(
    $txid,
    $revisao,
  );

  var_dump("Result", $result);

} catch(ForbbidenException $e) {
  var_dump("Recurso solicitado não foi encontrado.", $e->getMessage());
} catch(UnauthorizedException $e) {
  var_dump("Requisição de participante autenticado que viola alguma regra de autorização.", $e->getMessage());
} catch(ServicoIndisponivelException $e) {
  var_dump("Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.", $e->getMessage());
} catch(Exception $e) {
  var_dump("EXCEPT: ", $e->getMessage());
} 


