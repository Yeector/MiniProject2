    </main> 
    <footer class="text-center py-4 mt-auto" style="background: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="container text-muted">
            <small>&copy; <?= date('Y') ?> Polytechnic Course Registration System. Designed for DFP40443.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side form validation mapping
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    </body>
    </html>