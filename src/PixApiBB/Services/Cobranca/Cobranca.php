<?php

namespace PixApiBB\Services\Cobranca;

use Exception;
use PixApiBB\API\API;
use PixApiBB\Helpers\Response;
use PixApiBB\Services\Auth\ClientCredentials\ClientCredentials;
use PixApiBB\Services\Auth\ClientCredentials\IClientCredentials;
use PixApiBB\Services\Cobranca\Exceptions\ForbbidenException;
use PixApiBB\Services\Cobranca\Exceptions\ProblemaRequisicaoException;
use PixApiBB\Services\Cobranca\Exceptions\ServicoIndisponivelException;
use PixApiBB\Services\Cobranca\Exceptions\UnauthorizedException;
use PixApiBB\Services\Pix\Models\PixCobrancaModel;
use PixApiBB\Services\Service;
use stdClass;

class Cobranca extends Service implements ICobranca
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
   * Criar novo PIX QRCode Dinâmico
   * Method: POST
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function criar(
    $calendarioExpiracao,
    $devedorCnpj,
    $devedorNome,
    $valorOriginal,
    $chave,
    $solCnpjItAcaoPagador,
    array $infoAdicionais,
  )
  {

    $accessToken = $this->clientCredentials->getAccessToken();

    try {

      $guzzleResponse = $this->guzzleClient->post('cob', [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          'Content-Type' => 'application/json',
        ],
        'query' => [
          'gw-dev-app-key' => $this->api->devAppKey
        ],
        'json' => [
          'calendario' => [
            'expiracao' => $calendarioExpiracao
          ],
          'devedor' => [
            'cnpj' => $devedorCnpj,
            'nome' => $devedorNome
          ],
          'valor' => [
            'original' => $valorOriginal
          ],
          'chave' => $chave,
          'solcnpjitacaoPagador' => $solCnpjItAcaoPagador,
          'infoAdicionais' => $infoAdicionais
        ],
      ]);

      $response = Response::make($guzzleResponse);

      if ($response->success()) {
        return $response->body;
      } else {
        $this->throwException($response);
      }
    } catch (Exception $e) {
      throw new Exception($e);
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
    
    $accessToken = $this->clientCredentials->getAccessToken();

    try {

      $guzzleResponse = $this->guzzleClient->get('cob', [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          // 'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'query' => [
          'gw-dev-app-key' => $this->api->devAppKey,
          'dataInicio' => $dataInicio,
          'dataFim' => $dataFim,
          'cpf' => $cpf,
          'cnpj' => $cnpj,
          'locationPresente' => $locationPresente,
          'status' => $status,
          'paginaAtual' => $paginaAtual,
          'itensPorPagina' => $itensPorPagina,
        ],
      ]);

      $response = Response::make($guzzleResponse);

      if ($response->success()) {
        return $response->body;
      } else {
        $this->throwException($response);
      }
    } catch (Exception $e) {
      throw new Exception($e);
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
