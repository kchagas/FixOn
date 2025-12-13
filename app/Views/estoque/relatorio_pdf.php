<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title>Relatório de Movimentações - FixOn</title>

    <style>
        /* ======================================================
           RESET BÁSICO (PDF)
        ====================================================== */
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 30px;
        }

        /* ======================================================
           LOGO FIXON (DESTAQUE NO PDF)
        ====================================================== */
        .logo-pdf-wrapper {
            text-align: center;
            margin-bottom: 25px;
        }

       .logo-pdf {
    width: 220px;
    height: auto;
    display: block;
    margin: 0 auto;
}


        /* ======================================================
           CABEÇALHO DO RELATÓRIO
        ====================================================== */
        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 5px;
        }

        .subtitulo {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        /* ======================================================
           TABELA
        ====================================================== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #0b1e33;
            color: #ffffff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        tbody td {
            padding: 7px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f5f7fa;
        }

        /* ======================================================
           BADGES DE STATUS
        ====================================================== */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .entrada {
            background: #00c897;
            color: #ffffff;
        }

        .saida {
            background: #ff4d4f;
            color: #ffffff;
        }

        .ajuste {
            background: #ffc107;
            color: #000000;
        }

        .status-ok {
            background: #28a745;
            color: #fff;
        }

        .status-critico {
            background: #dc3545;
            color: #fff;
        }

        /* ======================================================
           RODAPÉ
        ====================================================== */
        .rodape {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>

<!-- =========================================================
     LOGO FIXON
========================================================= -->
<!-- LOGO FIXON -->
<div class="logo-pdf-wrapper">
    <?php if (!empty($logoBase64)): ?>
        <img
            src="<?= $logoBase64 ?>"
            class="logo-pdf"
            alt="FixOn - Gestão inteligente para serviços técnicos">
    <?php endif; ?>
</div>


<!-- =========================================================
     TÍTULO
========================================================= -->
<div class="titulo">
    Relatório de Movimentações de Estoque
</div>

<div class="subtitulo">
    Gerado em <?= date('d/m/Y H:i') ?>
</div>

<!-- =========================================================
     TABELA DE MOVIMENTAÇÕES
========================================================= -->
<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Tipo</th>
            <th>Qtd</th>
            <th>Peça</th>
            <th>Usuário</th>
            <th>Motivo</th>
            <th>Estoque Após</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($movimentacoes as $m): ?>

            <?php
                /* ================================
                   DEFINE CLASSES VISUAIS
                ================================= */
                $tipoClasse = match ($m['tipo']) {
                    'entrada' => 'entrada',
                    'saida'   => 'saida',
                    'ajuste'  => 'ajuste',
                    default   => ''
                };

                $statusClasse = ($m['estoque_atual'] <= $m['estoque_minimo'])
                    ? 'status-critico'
                    : 'status-ok';
            ?>

            <tr>
                <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>

                <td>
                    <span class="badge <?= $tipoClasse ?>">
                        <?= ucfirst($m['tipo']) ?>
                    </span>
                </td>

                <td><?= (int)$m['quantidade'] ?></td>

                <td>
                    <strong><?= esc($m['nome_peca']) ?></strong><br>
                    <small>SKU: <?= esc($m['sku']) ?></small>
                </td>

                <td>
                    <?= esc($m['nome_usuario'] ?? 'Sistema') ?>
                </td>

                <td><?= esc($m['motivo']) ?></td>

                <td><?= (int)$m['estoque_atual'] ?></td>

                <td>
                    <span class="badge <?= $statusClasse ?>">
                        <?= ($statusClasse === 'status-critico') ? 'Crítico' : 'OK' ?>
                    </span>
                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<!-- =========================================================
     RODAPÉ
========================================================= -->
<div class="rodape">
    FixOn SaaS • Gestão inteligente para serviços técnicos
</div>

</body>
</html>
