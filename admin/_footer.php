<?php
// admin/_footer.php
?>
    <!-- Page content ends here -->
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Mobile aside toggle
  const aside = document.getElementById('aside');
  const backdrop = document.getElementById('backdrop');
  const showBtn = document.getElementById('showAside');
  const hideBtn = document.getElementById('hideAside');

  function openAside() {
    aside.classList.add('open');
    backdrop.classList.add('show');
  }
  function closeAside() {
    aside.classList.remove('open');
    backdrop.classList.remove('show');
  }

  showBtn?.addEventListener('click', openAside);
  hideBtn?.addEventListener('click', closeAside);
  backdrop?.addEventListener('click', closeAside);

  // Close on ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAside();
  });
</script>
</body>
</html>
