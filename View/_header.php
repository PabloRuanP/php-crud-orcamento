<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema De Orçamentos e Relatório</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables  -->
    <link rel="stylesheet" href="../lib/DataTables/css/dataTables.bootstrap5.css">

    <!-- Bootstrap  -->
    <link rel="stylesheet" href="../lib/Bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../lib/Bootstrap/css/bootstrap.min.css">

    <!-- JQuery e DataTables -->
    <script src="../lib/jQuery/jquery-3.7.1.js"></script>
    <script src="../lib/DataTables/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="../source/css/style.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills"></i> Sistema De Orçamentos e Relatório
            </a>
        </div>
    </nav>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-bars"></i> Menu Principal</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <ul class="list-unstyled ps-0">
                            <!-- Home -->
                            <li class="mb-1">
                                <a href="index.php" class="btn btn-toggle align-items-center rounded  list-group-item list-group-item-action nav-link">
                                    <i class="fa-solid fa-house"></i> Home
                                </a>

                            </li>
                            <!-- Clientes -->
                            <li class="mb-1">
                                <button class="btn btn-toggle align-items-center rounded collapsed list-group-item list-group-item-action nav-link" data-bs-toggle="collapse"
                                    data-bs-target="#clientes-collapse" aria-expanded="false">
                                    <i class="fas fa-users me-2"></i> Clientes
                                </button>
                                <div class="collapse" id="clientes-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="cadastroCliente.php" class="link-dark rounded" data-section="clientes">Criar Cliente</a></li>
                                        <li><a href="clientes.php" class="link-dark rounded">Listar Clientes</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Vendedores -->
                            <li class="mb-1">
                                <button class="btn btn-toggle align-items-center rounded collapsed list-group-item list-group-item-action nav-link" data-bs-toggle="collapse"
                                    data-bs-target="#vendedores-collapse" aria-expanded="false">
                                    <i class="fas fa-user-tie me-2"></i> Vendedores
                                </button>
                                <div class="collapse" id="vendedores-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="cadastroVendedor.php" class="link-dark rounded">Criar Vendedor</a></li>
                                        <li><a href="vendedores.php" class="link-dark rounded">Listar Vendedores</a></li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Produtos -->
                            <li class="mb-1">
                                <button class="btn btn-toggle align-items-center rounded collapsed list-group-item list-group-item-action nav-link" data-bs-toggle="collapse"
                                    data-bs-target="#produtos-collapse" aria-expanded="false">
                                    <i class="fas fa-pills me-2"></i> Produtos
                                </button>
                                <div class="collapse" id="produtos-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="cadastroProduto.php" class="link-dark rounded">Criar Produto</a></li>
                                        <li><a href="produtos.php" class="link-dark rounded">Listar Produtos</a></li>
                                    </ul>
                                </div>
                            </li>


                            <!-- Ofertas -->
                            <li class="mb-1">
                                <button class="btn btn-toggle align-items-center rounded collapsed list-group-item list-group-item-action nav-link" data-bs-toggle="collapse"
                                    data-bs-target="#ofertas-collapse" aria-expanded="false">
                                    <i class="fas fa-tags me-2"></i> Ofertas
                                </button>
                                <div class="collapse" id="ofertas-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="cadastroOferta.php" class="link-dark rounded">Criar Oferta</a></li>
                                        <li><a href="ofertas.php" class="link-dark rounded">Listar Ofertas</a></li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Orçamentos -->
                            <li class="mb-1">
                                <button class="btn btn-toggle align-items-center rounded collapsed list-group-item list-group-item-action nav-link" data-bs-toggle="collapse"
                                    data-bs-target="#orcamentos-collapse" aria-expanded="false">
                                    <i class="fas fa-file-invoice-dollar me-2"></i> Orçamentos
                                </button>
                                <div class="collapse" id="orcamentos-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="cadastroOrcamento.php" class="link-dark rounded">Criar Orçamento</a></li>
                                        <li><a href="orcamentos.php" class="link-dark rounded">Listar Orçamentos</a></li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Relatórios -->
                            <li class="mb-1">
                                <a href="relatorios.php" class="btn btn-toggle text-start rounded collapsed nav-link">
                                    <i class="fas fa-chart-bar me-2"></i> Relatórios
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">