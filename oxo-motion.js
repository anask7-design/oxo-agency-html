(() => {
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const progress = document.createElement('div');
  progress.className = 'oxo-progress';
  progress.setAttribute('aria-hidden', 'true');
  document.body.append(progress);
  let scheduled = false;
  function updateProgress() {
    const available = document.documentElement.scrollHeight - innerHeight;
    progress.style.transform = `scaleX(${available > 0 ? Math.min(1, scrollY / available) : 0})`;
    scheduled = false;
  }
  addEventListener('scroll', () => {
    if (!scheduled) { scheduled = true; requestAnimationFrame(updateProgress); }
  }, { passive: true });
  addEventListener('resize', updateProgress, { passive: true });
  updateProgress();

  if (reduced) return;
  const targets = document.querySelectorAll('.hero h1, .hero .lead, .hero .actions, .hero .home-hero-panel, .section-head, .home-service, .home-proof, .step, .blog-card, .cta h2, .contact-card');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) { entry.target.classList.add('oxo-visible'); observer.unobserve(entry.target); }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });
    targets.forEach((element, index) => {
      element.classList.add('oxo-reveal');
      element.style.setProperty('--reveal-delay', `${index % 4 * 75}ms`);
      observer.observe(element);
    });
  }
  if (matchMedia('(hover:hover) and (pointer:fine)').matches) {
    const glow = document.createElement('div');
    glow.className = 'oxo-pointer';
    glow.setAttribute('aria-hidden', 'true');
    document.body.append(glow);
    let pointerFrame = 0, x = 0, y = 0;
    addEventListener('pointermove', event => {
      x = event.clientX; y = event.clientY;
      document.body.classList.add('pointer-active');
      if (!pointerFrame) pointerFrame = requestAnimationFrame(() => {
        glow.style.setProperty('--pointer-x', `${x}px`);
        glow.style.setProperty('--pointer-y', `${y}px`);
        pointerFrame = 0;
      });
    }, { passive: true });
    document.addEventListener('mouseleave', () => document.body.classList.remove('pointer-active'));
  }
})();

// GitHub Pages sert les pages statiques ; les formulaires sont traités par le serveur OXO.
if (location.hostname.endsWith('.github.io')) {
  document.querySelectorAll('form[action="devis.php"], form[action="contact.php"]').forEach(form => {
    form.action = 'https://web.oxo-agency.com/' + form.getAttribute('action');
  });
}

// Sur GitHub Pages, les mêmes pages sont disponibles avec des adresses sans extension.
if (location.hostname.endsWith('.github.io')) {
  document.querySelectorAll('a[href]').forEach(link => {
    const href = link.getAttribute('href');
    if (href === 'index.html') {
      link.setAttribute('href', '/oxo-agency-html/');
    } else if (/^(?:site-|blog-)[a-z0-9-]+\.html(?:[?#]|$)/.test(href)) {
      link.setAttribute('href', href.replace('.html', ''));
    }
  });
}
