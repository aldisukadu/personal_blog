    <!-- ============================================================
         FILE: layout/footer.php
         FUNGSI: Kerangka bagian BAWAH halaman (dipasang berulang)
         Berisi: penutup tag main, footer HTML, dan Bootstrap JS
         Cara pakai: include '../layout/footer.php'; di baris paling bawah
         ============================================================ -->
    
    </main>
    <!-- Tutup tag <main> yang dibuka di header.php -->
    
    <!-- FOOTER -->
    <!-- mt-auto = margin atas otomatis (mendorong footer ke bawah) -->
    <!-- py-3 = padding atas-bawah 3 unit -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <!-- date('Y') = menampilkan tahun saat ini secara otomatis -->
            <p class="mb-0">© <?= date('Y') ?> Solo Blog. Dibuat dengan PHP Native & Bootstrap 5.</p>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JavaScript dari CDN -->
    <!-- Harus di bawah (sebelum </body>) agar halaman lebih cepat dimuat -->
    <!-- 'bundle' artinya sudah termasuk Popper.js (untuk dropdown, dll) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
