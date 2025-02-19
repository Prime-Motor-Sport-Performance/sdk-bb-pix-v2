<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PixApiBB\Services\Auth\ClientCredentials\ClientCredentials;
use PixApiBB\Services\Pix\Pix;

final class PixTest extends TestCase
{
  public $config;

  public function __construct()
  {
    $config = require ('./../config.php');
  }

  public function testCanConsultaPixRecebidos()
  {
    
    $clientCredentials = ClientCredentials::make(
      $this->config['clientId'],
      $this->config['clientSecret'],
      $this->config['devAppKey'],
    );

    $pix = Pix::make($clientCredentials);

    $dataInicio = Carbon::parse('2020-04-01 23:59:59')->toIso8601String();
    $dataFim    = Carbon::parse('2020-04-05 23:59:59')->toIso8601String();
    $txId = "142314";
    $txIdPresente = false;
    $devolucaoPresente = false;
    $cpf = "34234233123";
    $cnpj = "34234233123";
    $paginaAtual = 0;
    $itensPorPagina = 100;

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

    $this->assertEquals(true, $result);

  }
}