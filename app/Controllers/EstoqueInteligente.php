<?php

namespace App\Controllers;

use App\Models\AlertaEstoqueModel;
use App\Services\EstoqueInteligenteService;

/**
 * ==========================================================
 * EstoqueInteligente (Passo 5.1)
 * ----------------------------------------------------------
 * Rotas principais:
 * - /estoque/alertas            -> Central de alertas
 * - /estoque/compras            -> Lista inteligente de compras
 * - /estoque/gerar-alertas      -> Gera/atualiza log de alertas
 * ==========================================================
 */
class EstoqueInteligente extends BaseController
{
    protected EstoqueInteligenteService $service;

    public function __construct()
    {
        $this->service = new EstoqueInteligenteService();
    }

    /**
     * Central de Alertas (visão de gestor)
     * Mostra apenas itens que não estão "ok".
     */
    public function alertas()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login')->with('error', 'Empresa não identificada.');
        }

        // Gera snapshot (cálculos do motor)
        $snap = $this->service->gerarSnapshot($empresaId);

        // Filtra para mostrar apenas níveis relevantes
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
     * Lista Inteligente de Compras
     * Mostra itens com sugestão > 0 (baseado na cobertura X dias).
     */
    public function compras()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login')->with('error', 'Empresa não identificada.');
        }

        $snap = $this->service->gerarSnapshot($empresaId);

        // Só entra o que tem compra sugerida (ERP real)
        $compras = array_filter($snap['itens'], function ($item) {
            return (int)$item['qtd_sugerida'] > 0;
        });

        // Ordena por nível (urgente primeiro), depois menor "dias_para_zerar"
        usort($compras, function ($a, $b) {

            $peso = ['urgente'=>1, 'critico'=>2, 'atencao'=>3, 'ok'=>4];

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
     * Gera log em alertas_estoque (interno)
     * - Útil para histórico e para o futuro WhatsApp (Passo 5.2)
     */
    public function gerarAlertas()
    {
        $empresaId = (int) session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login')->with('error', 'Empresa não identificada.');
        }

        $snap = $this->service->gerarSnapshot($empresaId);

        $alertaModel = new AlertaEstoqueModel();

        // Para MVP: cria um registro por item com nível != ok
        foreach ($snap['itens'] as $item) {

            if ($item['nivel'] === 'ok') {
                continue;
            }

            // Evita duplicar “igualzinho” toda hora:
            // - Aqui faremos um upsert simples: se existir alerta não resolvido para peça+nível, atualiza.
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
