<?php
require_once "_header.php";
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Bem-vindo ao Sistema de Gestão de Ofertas</h1>
        <p class="fs-5">Gerencie seus produtos, ofertas e muito mais de forma simples e eficiente.</p>
    </div>

    <div class="row g-4">

        <!-- Card Produtos -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-4 border-0 h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-pills mb-3" alt="Produtos" width="64"></i>
                    <h5 class="card-title fw-bold">Produtos</h5>
                    <p class="card-text">Cadastre e gerencie seus produtos.</p>
                    <a href="produtos.php" class="btn btn-primary">Listar Produtos</a>
                </div>
            </div>
        </div>

        <!-- Card Ofertas -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-4 border-0 h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tags me-2 mb-3" alt="Ofertas" width="64"></i>
                    <h5 class="card-title fw-bold">Ofertas</h5>
                    <p class="card-text">Crie ofertas e promoções especiais.</p>
                    <a href="ofertas.php" class="btn btn-success">Listar Ofertas</a>
                </div>
            </div>
        </div>

        <!-- Card Relatórios ou Configurações -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-4 border-0 h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar mb-3" alt="Relatórios" width="64"></i>
                    <h5 class="card-title fw-bold">Relatórios</h5>
                    <p class="card-text">Veja resumos e relatórios do seu sistema.</p>
                    <a href="relatorios.php" class="btn btn-dark">Ver Relatórios</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
require_once "_footer.php";
?>