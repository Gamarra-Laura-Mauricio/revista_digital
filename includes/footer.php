<!-- FOOTER MODULAR -->
<footer class="bg-dark text-white pt-5 pb-4 mt-auto" style="background-color: #1e1e1e !important;">
    <div class="container">
        <div class="row custom-g-4">
            
            <!-- Columna 1: Quiénes Somos -->
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-3 text-white">Quiénes Somos</h5>
                <p class="text-secondary small lh-lg mb-4" style="color: #b0b0b0 !important; max-width: 380px;">
                    Somos un espacio de periodismo independiente que busca visibilizar las acciones de diálogo en el país desde una mirada constructiva.
                </p>
                <div class="d-flex fs-5 custom-gap-3">
                    <a href="#" aria-label="Facebook" class="text-white text-decoration-none custom-opacity-75 hover-opacity-100"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="TikTok" class="text-white text-decoration-none custom-opacity-75 hover-opacity-100"><i class="fab fa-tiktok"></i></a>
                    <a href="#" aria-label="Instagram" class="text-white text-decoration-none custom-opacity-75 hover-opacity-100"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Columna 2: Contenido -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-3 text-white">Contenido</h5>
                <ul class="list-unstyled d-flex flex-column small custom-gap-2">
                    <li><a href="index.php#actualidad" class="text-decoration-none hover-text-white" style="color: #b0b0b0; transition: color 0.3s;">Noticias</a></li>
                    <li><a href="#" class="text-decoration-none hover-text-white" style="color: #b0b0b0; transition: color 0.3s;">Videos</a></li>
                    <li><a href="podcast.php" class="text-decoration-none hover-text-white" style="color: #b0b0b0; transition: color 0.3s;">Podcast</a></li>
                </ul>
            </div>

            <!-- Columna 3: Contacto -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3 text-white">Contacto</h5>
                <p class="small">
                    <a href="mailto:info@dialogoydesarrollo.com.pe" class="text-decoration-none hover-text-white" style="color: #b0b0b0; transition: color 0.3s;">
                        info@dialogoydesarrollo.com.pe
                    </a>
                </p>
            </div>

        </div>

        <hr class="my-4 border-secondary opacity-25">

        <!-- Copyright -->
        <div class="text-center">
            <p class="small mb-0" style="color: #b0b0b0;">
                © <?php echo date('Y'); ?> Diálogo y Desarrollo Perú. Todos los derechos reservados | Diseñado por <strong>WebSolutions</strong>
            </p>
        </div>
    </div>
</footer>

<!-- Botón Flotante para subir -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'});" 
        id="btnBackToTop"
        class="btn btn-danger position-fixed bottom-0 end-0 m-4 rounded-circle d-flex align-items-center justify-content-center shadow-lg" 
        style="width: 45px; height: 45px; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease;" 
        title="Volver arriba"
        aria-label="Volver arriba">
    <i class="fas fa-chevron-up fs-6"></i>
</button>

<script>
    // Mostrar el botón de volver arriba solo cuando se hace scroll
    window.addEventListener('scroll', function() {
        const btnTop = document.getElementById('btnBackToTop');
        if (window.scrollY > 300) {
            btnTop.style.opacity = '1';
            btnTop.style.visibility = 'visible';
        } else {
            btnTop.style.opacity = '0';
            btnTop.style.visibility = 'hidden';
        }
    });
</script>