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

  private IClientCredentials $clientCredentials;

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

    $this->clientCredentials = ClientCredentials::make($api);
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
    
    try {

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
        var_dump("success!", $response->body); die();
        return $response->body;
      } else {
        $this->throwException($response);
      }

    } catch(Exception $e) {
      throw new Exception($e);
    }

    // if ($response->success) {
    //   // return $pixModel;
    // } else {
    //   $this->throwException($this->exceptions, $response);
    // }

    // $code = 200;
    // switch ($code) {

    //   case 201:
    //     return Response::success('Lista dos Pix recebidos de acordo com o critério de busca.', []);
    //     break;
    //   case 400:
    //     return Response::error('Problemas na requisição.', []);
    //     break;
    //   case 401:
    //     return Response::error('Unauthorized.', []);
    //     break;
    //   case 403:
    //     return Response::error('Forbidden.', []);
    //     break;
    //   case 503:
    //     return Response::error('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
    //     break;
    // }

  }

  /**
   * Consultar PIX por TXID
   */
  public function consultarRecebido(string $e2eId)
  {
    $code = 200;

    switch ($code) {
      case 200:
        return Response::success('Dados do Pix efetuado', []);
        break;
      case 403:
        return Response::error('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 404:
        return Response::error('Recurso solicitado não foi encontrado.', []);
        break;
      case 503:
        return Response::error('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }

    return $this;
  }

  public function solicitarDevolucao($e2eId, $devolucaoId)
  {

    $code = 200;
    $response = [];

    return Response::make($response);
  }

  public function consultarDevolucao($e2eId, $devolucaoId)
  {
    $response = [];
    return Response::make($response);

    if ($response->success) {
      // return $pixModel;
    } else {
      $this->throwException($this->exceptions, $response);
    }
  }
}
