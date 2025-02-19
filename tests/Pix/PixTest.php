<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PixApiBB\API\API;
use PixApiBB\Services\Auth\ClientCredentials\ClientCredentials;
use PixApiBB\Services\Pix\Pix;

final class PixTest extends TestCase
{
  public $config;

  public function testCanConsultaPixRecebidos()
  {

    $config = require('config.php');

    $api = API::make(
      $config['client_id'],
      $config['client_secret'],
      $config['developer_application_key'],
      $config['api_url'],
    );

    $pix = Pix::make($api);

    $dataInicio = Carbon::parse('2020-04-01 23:59:59')->toIso8601String();
    $dataFim = Carbon::parse('2020-04-05 23:59:59')->toIso8601String();
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

      $this->assertNotEmpty($result, "O resultado de consulta não pode ser vazio");

    } catch (Exception $e) {

      $this->fail("Exceção lançada: " . $e->getMessage());

    }

  }
}
