<?php

// Permitindo que qualquer pessoa testem API da forma que quiser
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

try {
    $dados = json_decode(file_get_contents('php://input'));

    if ($dados === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Você precisa preecher as informações");
    }

    $calcTubulacao = floatval($dados->tubulacao);
    $calcProfundidade = floatval($dados->profundidade);
    $calcDistancia = floatval($dados->distancia);
    $calcVazao = floatval($dados->vazao);
    $calcAltura = floatval($dados->altura);

    function calcularMCA($altura, $alturaReservatorio, $comprimento, $diametro, $vazaoDesejada, $material = 140)
    {
        $hf = (10.9611 * (pow((($vazaoDesejada / (1000 * 3600)) / $material), 1.852)) * $comprimento / (pow(($diametro / 1000), 4.8655)));

        $mcaTotal = $hf + $altura + $alturaReservatorio;

        return number_format($mcaTotal, 2, ',', '.');
    }

    if (
        !empty($calcTubulacao)
        && !empty($calcProfundidade)
        && !empty($calcDistancia)
        && !empty($calcVazao)
        && !empty($calcAltura)
    ) {
        exit(json_encode(array('status' => true, 'result' => calcularMCA($calcProfundidade, $calcAltura, $calcDistancia, $calcTubulacao, $calcVazao))));
    } else {
        exit(json_encode(array('status' => false)));
    }
} catch (Exception $e) {
    //throw $th;
    exit(json_encode(array('message' => $e->getMessage())));
}   
