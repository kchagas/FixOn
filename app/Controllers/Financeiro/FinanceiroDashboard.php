<?php

namespace App\Controllers\Financeiro; // Define o namespace para subpasta Financeiro (boa prática)

use App\Controllers\BaseController;   // Importa o BaseController do projeto
use App\Models\ContaPagarModel;       // Importa o Model de Contas a Pagar

/**
 * ==========================================================
 * CONTROLLER: FinanceiroDashboard
 * ----------------------------------------------------------
 * Objetivo:
 * - Exibir visão gerencial do Contas a Pagar (FASE 1)
 * - Cards (KPIs) + Lista rápida de vencimentos
 *
 * URL:
 * - /financeiro/dashboard
 * ==========================================================
 */
class FinanceiroDashboard extends BaseController
{
    /**
     * ------------------------------------------------------
     * MÉTODO: index()
     * ------------------------------------------------------
     * Responsável por montar os dados e carregar a view
     */
    public function index()
    {
        // Cria instância do model
        $model = new ContaPagarModel();

        // Recupera o ID da empresa da sessão (SaaS)
        $empresaId = (int) session()->get('empresa_id');

        // Se não tiver empresa na sessão, força login
        if (!$empresaId) {
            // Redireciona para login com mensagem
            return redirect()->to('/login')->with('error', 'Empresa não identificada.');
        }

        // Busca o resumo (KPIs) do dashboard
        $resumo = $model->getResumoDashboard($empresaId);

        // Busca contas que vencem nos próximos 7 dias (lista rápida)
        $vencendo = $model->listarVencendo($empresaId, 7);

        // Busca contas vencidas (lista rápida opcional para dar “peso” no dashboard)
        $vencidas = $model->listarVencidas($empresaId, 10);

        // Carrega a view com os dados
        return view('financeiro/contas_pagar/dashboard', [
            'title'    => 'Dashboard Financeiro',
            'resumo'   => $resumo,
            'vencendo' => $vencendo,
            'vencidas' => $vencidas,
        ]);
    }
}
