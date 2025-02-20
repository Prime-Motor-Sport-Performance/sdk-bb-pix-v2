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
);

$pix = Pix::make($api);

$e2eId = "E12345678202009091221abcdef12345";

try {

  $result = $pix->consultarRecebido($e2eId);
  var_dump($result);

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
