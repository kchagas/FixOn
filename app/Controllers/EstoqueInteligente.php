<?php

namespace App\Controllers;

use App\Models\AlertaEstoqueModel;
use App\Services\EstoqueInteligenteService;

/**
 * ==========================================================
 * EstoqueInteligente (Passo 5.1)
 * ----------------------------------------------------------
 * - Central de Alertas
 * - Compras Inteligentes
 * - Geração de alertas (snapshot)
 * ==========================================================
 */
class EstoqueInteligente extends BaseController
{
    /**
     * Motor inteligente de estoque
     */
    protected EstoqueInteligenteService $service;

    public function __construct()
    {
        // Inicializa o motor inteligente
        $this->service = new EstoqueInteligenteService();
    }

    /**
     * CENTRAL DE ALERTAS
     * Mostra apenas itens com risco (≠ ok)
     */
    public function alertas()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()
                ->to('/login')
                ->with('error', 'Empresa não identificada.');
        }

        // Snapshot do motor
        $snap = $this->service->gerarSnapshot($empresaId);

        // Filtra níveis relevantes
        $alertas = array_filter($snap['itens'], function ($item) {
            return in_array($item['nivel'], ['urgente', 'critico', 'atencao'], true);
        });

        return view('estoque/alertas', [
            'title'   => 'Central de Alertas',
            'config'  => $snap['config'],
            'alertas' => $alertas,
        ]);
    }

    /**
     * COMPRAS INTELIGENTES
     * Mostra apenas itens com sugestão > 0
     */
    public function compras()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()
                ->to('/login')
                ->with('error', 'Empresa não identificada.');
        }

        $snap = $this->service->gerarSnapshot($empresaId);

        // Apenas itens com sugestão de compra
        $compras = array_filter($snap['itens'], function ($item) {
            return (int) $item['qtd_sugerida'] > 0;
        });

        // Ordenação inteligente
        usort($compras, function ($a, $b) {

            $peso = [
                'urgente' => 1,
                'critico' => 2,
                'atencao' => 3,
                'ok'      => 4,
            ];

            $pa = $peso[$a['nivel']] ?? 99;
            $pb = $peso[$b['nivel']] ?? 99;

            if ($pa !== $pb) {
                return $pa <=> $pb;
            }

            $da = $a['dias_para_zerar'] ?? 999999;
            $db = $b['dias_para_zerar'] ?? 999999;

            return $da <=> $db;
        });

        return view('estoque/compras_inteligentes', [
            'title'   => 'Compras Inteligentes',
            'config'  => $snap['config'],
            'compras' => $compras,
        ]);
    }

    /**
     * GERA ALERTAS (LOG)
     * Atualiza tabela alertas_estoque
     */
    public function gerarAlertas()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()
                ->to('/login')
                ->with('error', 'Empresa não identificada.');
        }

        $snap = $this->service->gerarSnapshot($empresaId);

        $alertaModel = new AlertaEstoqueModel();

        foreach ($snap['itens'] as $item) {

            // Ignora itens OK
            if ($item['nivel'] === 'ok') {
                continue;
            }

            // Verifica alerta aberto
            $existente = $alertaModel
                ->where('empresa_id', $empresaId)
                ->where('peca_id', $item['peca_id'])
                ->where('resolvido', 0)
                ->first();

            $payload = [
                'empresa_id'        => $empresaId,
                'peca_id'           => $item['peca_id'],
                'nivel'             => $item['nivel'],
                'estoque_atual'     => $item['estoque_atual'],
                'estoque_minimo'    => $item['estoque_minimo'],
                'consumo_medio_dia' => $item['consumo_medio_dia'],
                'dias_para_zerar'   => $item['dias_para_zerar'],
                'qtd_sugerida'      => $item['qtd_sugerida'],
                'mensagem'          => $item['mensagem'],
                'resolvido'         => 0,
            ];

            if ($existente) {
                $payload['id'] = $existente['id'];
            }

            $alertaModel->save($payload);
        }

        return redirect()
            ->to('/estoque/alertas')
            ->with('success', 'Alertas atualizados com sucesso!');
    }
}
