<?php

require 'vendor/autoload.php'; // Ajuste o caminho se necessário

use Carbon\Carbon;
use PixApiBB\API\API;
use PixApiBB\Services\Pix\Exceptions\ForbbidenException;
use PixApiBB\Services\Pix\Exceptions\ProblemaRequisicaoException;
use PixApiBB\Services\Pix\Exceptions\ServicoIndisponivelException;
use PixApiBB\Services\Pix\Exceptions\UnauthorizedException;
use PixApiBB\Services\Pix\Pix;

$config = require('config.php');

$api = API::make(
  $config['client_id'],
  $config['client_secret'],
  $config['developer_application_key'],
  $config['api_url'],
  $config['auth_url'],
  $config['cert'],
  $config['ssl_key'],
  $config['debug_mode'],
);

$pix = Pix::make($api);

$dataInicio = Carbon::parse('2025-02-19 23:59:59')->toIso8601String();
$dataFim = Carbon::parse('2025-02-21 23:59:59')->toIso8601String();
$txId = "142314";
$txIdPresente = false;
$devolucaoPresente = false;
$cpf = "34234233123";
$cnpj = "";
$paginaAtual = 0;
$itensPorPagina = 100;

try {

  $result = $pix->consultarRecebidos(
    $dataInicio,
    $dataFim,
    $txId,
    $txIdPresente,
    $devolucaoPresente,
    $cpf,
    $cnpj,
    $paginaAtual,
    $itensPorPagina
  );

} catch (ProblemaRequisicaoException $e) {
  var_dump("Problema na requisição.", $e->getMessage());
} catch (UnauthorizedException $e) {
  var_dump("Requisição de participante autenticado que viola alguma regra de autorização.", $e->getMessage());
} catch (ForbbidenException $e) {
  var_dump("Cobrança não encontrada.", $e->getMessage());
} catch (ServicoIndisponivelException $e) {
  var_dump("Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.", $e->getMessage());
} catch(Exception $e) {
  var_dump("EXCEPT: ", $e->getMessage());
}

