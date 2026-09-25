<!-- FOOTER MODULAR -->
<footer class="bg-dark text-white pt-5 pb-4 mt-auto" style="background-color: #1e1e1e !important;">
    <div class="container">
        <div class="row g-4">
            
            <!-- Columna 1: Quiénes Somos -->
            <div class="col-lg-5 col-md-6">
                <h5 class="fw-bold mb-3 text-white">Quiénes Somos</h5>
                <p class="text-secondary small lh-lg mb-4" style="color: #b0b0b0 !important; max-width: 380px;">
                    Somos un espacio de periodismo independiente que busca visibilizar las acciones de diálogo en el país desde una mirada constructiva.
                </p>
                <div class="d-flex gap-3 fs-5">
                    <a href="#" class="text-white text-decoration-none opacity-75 opacity-100-hover"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white text-decoration-none opacity-75 opacity-100-hover"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="text-white text-decoration-none opacity-75 opacity-100-hover"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Columna 2: Contenido -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3 text-white">Contenido</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 small">
                    <li><a href="index.php#actualidad" class="text-decoration-none" style="color: #b0b0b0;">Noticias</a></li>
                    <li><a href="#" class="text-decoration-none" style="color: #b0b0b0;">Videos</a></li>
                    <li><a href="podcast.php" class="text-decoration-none" style="color: #b0b0b0;">Posdcast.</a></li>
                </ul>
            </div>

            <!-- Columna 3: Contacto -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3 text-white">Contacto</h5>
                <p class="small" style="color: #b0b0b0;">
                    info@dialogoydesarrollo.com.pe
                </p>
            </div>

        </div>

        <hr class="my-4 border-secondary opacity-25">

        <!-- Copyright -->
        <div class="text-center">
            <p class="small mb-0" style="color: #b0b0b0;">
                © <?php echo date('Y'); ?> Diálogo y Desarrollo Perú. All rights reserved | Designed by <strong>WebSolutions</strong>
            </p>
        </div>
    </div>
</footer>

<!-- Botón Flotante para subir -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'});" 
        class="btn btn-danger position-fixed bottom-0 end-0 m-4 rounded-2 d-flex align-items-center justify-content-center shadow" 
        style="width: 38px; height: 38px; z-index: 1000;" 
        title="Volver arriba">
    <i class="fas fa-chevron-up fs-6"></i>
</button>