<?php

namespace PixApiBB\Services\Pix;

use Exception;
use PixApiBB\Helpers\Response;
use PixApiBB\API\API;
use PixApiBB\Services\Auth\ClientCredentials\ClientCredentials;
use PixApiBB\Services\Auth\ClientCredentials\IClientCredentials;
use PixApiBB\Services\Pix\Exceptions\ForbbidenException;
use PixApiBB\Services\Pix\Exceptions\ProblemaRequisicaoException;
use PixApiBB\Services\Pix\Exceptions\ServicoIndisponivelException;
use PixApiBB\Services\Pix\Exceptions\UnauthorizedException;
use PixApiBB\Services\Service;

class Pix extends Service implements IPix
{

  public static function make(API $api)
  {
    return new self($api);
  }

  public function __construct(API $api)
  {

    parent::__construct($api);

    $this->exceptions = [
      400 => ProblemaRequisicaoException::class,
      401 => UnauthorizedException::class,
      403 => ForbbidenException::class,
      404 => ForbbidenException::class,
      503 => ServicoIndisponivelException::class,
    ];
  }

  /******************/
  /* API Endpoints */
  /******************/

  /**
   * Consultar PIX
   * Method: GET
   * Endpoint: /cob
   */
  public function consultarRecebidos(
    string $dataInicio,
    string $dataFim,
    string $txId,
    bool $txIdPresente,
    bool $devolucaoPresente,
    string $cpf,
    string $cnpj,
    int $paginaAtual = 0,
    int $itensPorPagina = 100
  ) {

    $accessToken = $this->clientCredentials->getAccessToken();

    $guzzleResponse = $this->guzzleClient->get('pix', [
      'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'query' => [
        'gw-dev-app-key'            => $this->api->devAppKey,
        'inicio'                    => $dataInicio,
        'fim'                       => $dataFim,
        'txid'                      => $txId,
        'txIdPresente'              => $txIdPresente,
        'devolucaoPresente'         => $devolucaoPresente,
        'cpf'                       => $cpf,
        'cnpj'                      => $cnpj,
        'paginacao.paginaAtual'     => $paginaAtual,
        'paginacao.itensPorPagina'  => $itensPorPagina,
      ],
    ]);

    $response = Response::make($guzzleResponse);

    if ($response->success()) {
      var_dump("success!", $response->body);
      die();
      return $response->body;
    } else {
      $this->throwException($response);
    }
  }

  /**
   * Consultar PIX por TXID
   */
  public function consultarRecebido(string $e2eId)
  {

    $accessToken = $this->clientCredentials->getAccessToken();

    $guzzleResponse = $this->guzzleClient->get('pix/' . $e2eId, [
      'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'query' => [
        'gw-dev-app-key'            => $this->api->devAppKey,
      ],
    ]);

    $response = Response::make($guzzleResponse);

    if ($response->success()) {
      var_dump("success!", $response->body);
      die();
      return $response->body;
    } else {
      $this->throwException($response);
    }
  }

  // public function solicitarDevolucao($e2eId, $devolucaoId)
  // {

  //   $code = 200;
  //   $response = [];

  //   return Response::make($response);
  // }

  // public function consultarDevolucao($e2eId, $devolucaoId)
  // {
  //   $response = [];
  //   return Response::make($response);

  //   if ($response->success) {
  //     // return $pixModel;
  //   } else {
  //     $this->throwException($this->exceptions, $response);
  //   }
  // }
}
