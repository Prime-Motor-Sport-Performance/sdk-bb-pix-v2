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

$dataInicio = Carbon::parse('2025-02-19 00:00:00')->toIso8601String();
$dataFim = Carbon::parse('2025-02-19 23:59:59')->toIso8601String();
$cpf = "";
$cnpj = "12345678000195";
$paginaAtual = 0;
$itensPorPagina = 100;
$locationPresente = false;
$status = Cobranca::ATIVA;

$pixCobranca = Cobranca::make($api);

try {

  $result = $pixCobranca->consultarLista(
    $dataInicio,
    $dataFim,
    $cpf,
    $cnpj,
    $locationPresente,
    $status,
    $paginaAtual,
    $itensPorPagina
  );

  var_dump("Result", $result);

} catch(ForbbidenException $e) {
  var_dump("Nenhuma cobrança encontrada!", $e->getMessage());
} catch(UnauthorizedException $e) {
  var_dump("Requisição de participante autenticado que viola alguma regra de autorização.", $e->getMessage());
} catch(ServicoIndisponivelException $e) {
  var_dump("Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.", $e->getMessage());
} catch(Exception $e) {
  var_dump("EXCEPT: ", $e->getMessage());
} 

