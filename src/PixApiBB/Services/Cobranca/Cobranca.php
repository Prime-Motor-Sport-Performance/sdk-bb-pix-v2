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

  // ATIVA: a cobrança Pix foi gerada e está pronta para ser liquidada;
  // CONCLUIDA: a cobrança Pix foi liquidada. Não se pode alterar e nem cancelar uma cobrança Pix com status “concluída”;
  // REMOVIDA_PELO_USUARIO_RECEBEDOR: a cobrança Pix foi cancelada (removida) pelo usuário recebedor;
  // REMOVIDA_PELO_PSP: a cobrança Pix, por conta de algum critério, foi cancelada (removida) pelo Banco.

  public const ATIVA = "ATIVA";
  public const CONCLUIDA = "CONCLUIDA";
  public const REMOVIDA_PELO_USUARIO_RECEBEDOR = "REMOVIDA_PELO_USUARIO_RECEBEDOR";
  public const REMOVIDA_PELO_PSP = "REMOVIDA_PELO_PSP";

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
    $devedorCnpj = null,
    $devedorCpf = null,
    $devedorNome,
    $valorOriginal,
    $chave,
    $solCnpjItAcaoPagador,
    array $infoAdicionais,
  ) {

    $accessToken = $this->clientCredentials->getAccessToken();

    $jsonData = [
      'calendario' => [
        'expiracao' => $calendarioExpiracao
      ],
      'valor' => [
        'original' => $valorOriginal
      ],
      'chave' => $chave,
      'solcnpjitacaoPagador' => $solCnpjItAcaoPagador,
      'infoAdicionais' => $infoAdicionais
    ];

    // TODO, quando nem CNPJ e nem CPF for fornecido, throw SemDocumentoException
    if ($devedorCnpj) {

      $jsonData['devedor'] = [
        'cnpj' => $devedorCnpj,
        'nome' => $devedorNome
      ];

    } else {

      $jsonData['devedor'] = [
        'cpf' => $devedorCpf,
        'nome' => $devedorNome
      ];

    }

    $guzzleResponse = $this->guzzleClient->post('cob', [
      'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
      ],
      'query' => [
        'gw-dev-app-key' => $this->api->devAppKey
      ],
      'json' => $jsonData,
    ]);

    $response = Response::make($guzzleResponse);

    if ($response->success()) {
      return $response->body;
    } else {
      $this->throwException($response);
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

    $guzzleResponse = $this->guzzleClient->get('cob', [
      'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'query' => [
        'gw-dev-app-key' => $this->api->devAppKey,
        'inicio' => $dataInicio,
        'fim' => $dataFim,
        'cpf' => $cpf,
        'cnpj' => $cnpj,
        'locationPresente' => $locationPresente,
        'status' => $status,
        'paginacao.paginaAtual' => $paginaAtual,
        'paginacao.itensPorPagina' => $itensPorPagina,
      ],
    ]);

    $response = Response::make($guzzleResponse);

    if ($response->success()) {
      return $response->body;
    } else {
      $this->throwException($response);
    }
  }

  /**
   * Criar novo PIX QRCode Dinâmico com TXID
   * Method: PUT
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function cirarComTxId(
    $txId,
    $calendarioExpiracao,
    $devedorCnpj,
    $devedorCpf,
    $devedorNome,
    $valorOriginal,
    $chave,
    $solCnpjItAcaoPagador,
    array $infoAdicionais,
  ) {

    $accessToken = $this->clientCredentials->getAccessToken();

    $jsonData = [
      'calendario' => [
        'expiracao' => $calendarioExpiracao
      ],
      'valor' => [
        'original' => $valorOriginal
      ],
      'chave' => $chave,
      'solcnpjitacaoPagador' => $solCnpjItAcaoPagador,
      'infoAdicionais' => $infoAdicionais
    ];

    if ($devedorCnpj) {

      $jsonData['devedor'] = [
        'cnpj' => $devedorCnpj,
        'nome' => $devedorNome
      ];

    } else {

      $jsonData['devedor'] = [
        'cpf' => $devedorCpf,
        'nome' => $devedorNome
      ];

    }

    try {

      $guzzleResponse = $this->guzzleClient->put('cob/' . $txId, [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          'Content-Type' => 'application/json',
        ],
        'query' => [
          'gw-dev-app-key' => $this->api->devAppKey
        ],
        'json' => $jsonData,
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
   * Revisar PIX QRCode Dinâmico
   * Method: PATCH
   * Endpoint: /cob
   * 
   * Atenção, os valores só poderão ser atualizados caso o status da cobrança seja ATIVA!
   */
  public function revisarComTxId(
    $txId,
    $locId = null,
    $devedorNome = null,
    $devedorCpf = null,
    $devedorCnpj = null,
    $valorOriginal = null,
    $solicitacaoPagador = null,
    $status = null
  ) {

    $accessToken = $this->clientCredentials->getAccessToken();

    $payload = [];

    if ($locId) {
      $payload['loc']['id'] = $locId;
    }

    if ($devedorNome) {

      $payload['devedor']['nome'] = $devedorNome;

      if ($devedorCnpj) {
        $payload['devedor']['cnpj'] = $devedorCnpj;
      } else {
        $payload['devedor']['cpf'] = $devedorCpf;
      }

    }

    if ($valorOriginal) {
      $payload['valor']['original'] = $valorOriginal;
    }

    if ($solicitacaoPagador) {
      $payload['solicitacaoPagador'] = $solicitacaoPagador;
    }

    if ($status) {
      $payload['status'] = $status;
    }

    try {

      $guzzleResponse = $this->guzzleClient->patch('cob/' . $txId, [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          'Content-Type' => 'application/json',
        ],
        'query' => [
          'gw-dev-app-key' => $this->api->devAppKey
        ],
        'json' => $payload
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
   * Consultar PIX por TXID
   */
  public function consultarComTxId($txId, $revisao = null)
  {
   
    $accessToken = $this->clientCredentials->getAccessToken();

    $guzzleResponse = $this->guzzleClient->get('cob/' . $txId, [
      'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'query' => [
        'gw-dev-app-key' => $this->api->devAppKey,
        'revisao' => $revisao,
      ],
    ]);

    $response = Response::make($guzzleResponse);

    if ($response->success()) {
      return $response->body;
    } else {
      $this->throwException($response);
    }
  }
}
