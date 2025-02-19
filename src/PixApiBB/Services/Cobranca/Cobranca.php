<?php

namespace PixApiBB\Services\Cobranca;

use PixApiBB\Helpers\Response;
use PixApiBB\Services\Auth\IClientCredentials;

class Cobranca implements ICobranca
{
  
  private IClientCredentials $clientCredentials;

  public static function make(IClientCredentials $clientCredentials)
  {
    return new self($clientCredentials);
  }

  public function __construct(IClientCredentials $clientCredentials)
  {
    $this->clientCredentials = $clientCredentials;
  }

  /******************/
  /* API Endpoints */
  /******************/

  /**
   * Criar novo PIX QRCode Dinâmico
   * Method: POST
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function criar($txId = null)
  {
    $code = 200;

    switch ($code) {

      case 201:
        return Response::success('Cobrança imediata criada.', []);
        break;
      case 400:
        return Response::error('Requisição com formato inválido.', []);
        break;
      case 403:
        return Response::error('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 503:
        return Response::error('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }
  }

  /**
   * Consultar PIX
   * Method: GET
   * Endpoint: /cob
   */
  public function consultarLista(
    string $dataInicio,
    string $dataFim,
    string $cpf,
    string $cnpj,
    bool $locationPresente,
    string $status,
    int $paginaAtual = 0,
    int $itensPorPagina = 100
  ) {
    $code = 200;

    switch ($code) {

      case 201:
        return Response::success('Lista de cobranças imediatas.', []);
        break;
      case 403:
        return Response::error('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 503:
        return Response::error('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }
  }

  /**
   * Criar novo PIX QRCode Dinâmico com TXID
   * Method: PUT
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function cirarComTxId($txId)
  {
    $code = 200;

    switch ($code) {

      case 201:
        return Response::success('Cobrança imediata criada.', []);
        break;
      case 400:
        return Response::error('Requisição com formato inválido.', []);
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
    
  }

  /**
   * Revisar PIX QRCode Dinâmico
   * Method: PATCH
   * Endpoint: /cob
   */
  public function revisarComTxId($txId)
  {
    $code = 200;

    switch ($code) {
      case 200:
        return Response::success('Cobrança imediata revisada. A revisão deve ser incrementada em 1.', []);
        break;
      case 400:
        return Response::error('Requisição com formato inválido.', []);
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
  }

  /**
   * Consultar PIX por TXID
   */
  public function consultar($txId, $revisao = null)
  {
    $code = 200;

    switch ($code) {
      case 200:
        return Response::success('Dados da cobrança imediata.', []);
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

}
