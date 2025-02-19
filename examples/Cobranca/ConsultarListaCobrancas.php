<?php

require 'vendor/autoload.php'; // Ajuste o caminho se necessário

use Carbon\Carbon;
use PixApiBB\API\API;
use PixApiBB\Services\Cobranca\Cobranca;

$config = require('config.php');

$api = API::make(
  $config['client_id'],
  $config['client_secret'],
  $config['developer_application_key'],
  $config['api_url'],
  $config['auth_url'],
  $config['debug_mode'],
);

$dataInicio = Carbon::parse('2020-04-01 23:59:59')->toIso8601String();
$dataFim = Carbon::parse('2020-04-05 23:59:59')->toIso8601String();
$cpf = "";
$cnpj = "12345678000195";
$paginaAtual = 0;
$itensPorPagina = 100;
$locationPresente = false;
$status = "";

$pixCobranca = Cobranca::make($api);

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

var_dump($result);
