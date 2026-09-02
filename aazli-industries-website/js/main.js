// ---------- Load products from the database (falls back to the static file list
// in products-data.js if php/get-products.php isn't reachable, e.g. opened as a
// plain file instead of through Apache) ----------
async function loadProductsFromServer() {
  try {
    const res = await fetch('php/get-products.php');
    const data = await res.json();
    if (data.ok && Array.isArray(data.products) && data.products.length) {
      window.AAZLI_PRODUCTS = data.products;
    }
  } catch (e) {
    // Silently keep the static fallback from products-data.js
  }
}

// ---------- Mobile menu ----------
document.addEventListener('DOMContentLoaded', async () => {
  await loadProductsFromServer();
  const btn = document.getElementById('menuBtn');
  const menu = document.getElementById('mobileMenu');
  const closeBtn = document.getElementById('menuCloseBtn');
  if (btn && menu) {
    btn.addEventListener('click', () => menu.classList.add('open'));
  }
  if (closeBtn && menu) {
    closeBtn.addEventListener('click', () => menu.classList.remove('open'));
  }
  document.querySelectorAll('.mobile-menu a').forEach(a => {
    a.addEventListener('click', () => menu && menu.classList.remove('open'));
  });

  // ---------- FAQ accordion ----------
  document.querySelectorAll('.faq-item').forEach(item => {
    const q = item.querySelector('.faq-q');
    const a = item.querySelector('.faq-a');
    const icon = item.querySelector('.faq-icon');
    if (!q || !a) return;
    a.style.maxHeight = '0px';
    q.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(o => {
        o.classList.remove('open');
        o.querySelector('.faq-a').style.maxHeight = '0px';
        const oi = o.querySelector('.faq-icon'); if (oi) oi.textContent = '+';
      });
      if (!isOpen) {
        item.classList.add('open');
        a.style.maxHeight = a.scrollHeight + 20 + 'px';
        if (icon) icon.textContent = '\u2212';
      }
    });
  });

  // ---------- Forms: POST to php/submit-inquiry.php (requires Apache+MySQL, e.g. via XAMPP) ----------
  document.querySelectorAll('form[data-demo-form]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalLabel = submitBtn ? submitBtn.textContent : '';
      if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending…'; }

      const payload = { formType: form.dataset.formType || 'contact' };
      new FormData(form).forEach((value, key) => { payload[key] = value; });

      let resultMsg, isError = false;
      try {
        const res = await fetch('php/submit-inquiry.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          resultMsg = "Thanks — your inquiry has been received. We'll be in touch shortly.";
          form.reset();
        } else {
          isError = true;
          resultMsg = data.error || 'Something went wrong submitting the form. Please try again.';
        }
      } catch (err) {
        isError = true;
        resultMsg = "Couldn't reach the server. If you're running this from a plain file (file://), start it through XAMPP/Apache instead — see the setup notes.";
      }

      if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalLabel; }

      const existing = form.nextElementSibling;
      if (existing && existing.classList.contains('form-result-msg')) existing.remove();
      const box = document.createElement('div');
      box.className = 'form-result-msg mt-6 p-5 border text-sm font-medium ' +
        (isError ? 'border-red-200 bg-red-50 text-red-700' : 'border-green-200 bg-green-50 text-green-800');
      box.textContent = resultMsg;
      form.insertAdjacentElement('afterend', box);
      if (!isError) setTimeout(() => box.remove(), 7000);
    });
  });

  renderCategoryGrids();
  renderFeaturedProducts();
  renderProductGrid();
  renderProductDetail();
  renderRelatedProducts();
  populateQuoteProductSelect();

  // ---------- Reveal on scroll (runs AFTER products are rendered so the ----------
  // ---------- dynamically-created cards are picked up by the observer)   ----------
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('in'));
  }
});

// ---------- Product rendering (uses window.AAZLI_PRODUCTS from products-data.js) ----------
function productCard(p, compact) {
  return `
  <a href="product-detail.html?id=${p.id}" class="card cut-card group flex flex-col overflow-hidden reveal">
    <div class="aspect-[4/3] w-full flex items-center justify-center relative overflow-hidden" style="background:${p.swatch}">
      <span class="font-display text-white/90 text-sm tracking-wide">${p.category}</span>
      <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
    </div>
    <div class="p-5 flex-1 flex flex-col">
      <span class="eyebrow">${p.category}</span>
      <h3 class="font-display text-lg font-semibold mt-1 mb-2">${p.name}</h3>
      ${!compact ? `<p class="text-sm text-[var(--slate)] leading-relaxed mb-4">${p.short}</p>` : ''}
      <div class="mt-auto flex items-center justify-between pt-3 border-t border-[var(--line)]">
        <span class="text-xs font-semibold text-[var(--slate)]">MOQ: ${p.moq}</span>
        <span class="text-sm font-semibold grad-text">View &rarr;</span>
      </div>
    </div>
  </a>`;
}

function renderFeaturedProducts() {
  const el = document.getElementById('featuredProducts');
  if (!el || !window.AAZLI_PRODUCTS) return;
  const featured = window.AAZLI_PRODUCTS.filter(p => p.featured);
  el.innerHTML = featured.map(p => productCard(p, true)).join('');
}

function renderProductGrid() {
  const el = document.getElementById('productGrid');
  if (!el || !window.AAZLI_PRODUCTS) return;
  const params = new URLSearchParams(location.search);
  const cat = params.get('category');
  const countEl = document.getElementById('productCount');

  function draw(list) {
    el.innerHTML = list.map(p => productCard(p, true)).join('') || `<p class="col-span-full text-[var(--slate)]">No products match your search.</p>`;
    if (countEl) countEl.textContent = list.length + ' product' + (list.length === 1 ? '' : 's');
  }

  draw(cat ? window.AAZLI_PRODUCTS.filter(p => p.categorySlug === cat) : window.AAZLI_PRODUCTS);

  const search = document.getElementById('productSearch');
  const catSelect = document.getElementById('categoryFilter');
  if (catSelect) {
    catSelect.value = cat || 'all';
    catSelect.addEventListener('change', () => filterAndDraw());
  }
  if (search) search.addEventListener('input', () => filterAndDraw());

  function filterAndDraw() {
    const term = (search && search.value.trim().toLowerCase()) || '';
    const c = (catSelect && catSelect.value) || 'all';
    let list = window.AAZLI_PRODUCTS;
    if (c !== 'all') list = list.filter(p => p.categorySlug === c);
    if (term) list = list.filter(p => (p.name + p.short + p.category).toLowerCase().includes(term));
    draw(list);
  }
}

function renderCategoryGrids() {
  const el = document.getElementById('categoryGrid');
  if (!el || !window.AAZLI_CATEGORIES) return;
  el.innerHTML = window.AAZLI_CATEGORIES.map(c => `
    <a href="products.html?category=${c.slug}" class="group relative overflow-hidden cut-card reveal" style="background:${c.swatch}">
      <div class="aspect-[5/4] flex flex-col justify-end p-6 text-white relative">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
        <span class="relative z-10 eyebrow !text-white/80">${c.count}</span>
        <h3 class="relative z-10 font-display text-xl font-semibold mt-1">${c.name}</h3>
        <span class="relative z-10 text-sm mt-2 text-white/85 group-hover:translate-x-1 transition inline-block">Explore category &rarr;</span>
      </div>
    </a>
  `).join('');
}

function renderProductDetail() {
  const el = document.getElementById('productDetail');
  if (!el || !window.AAZLI_PRODUCTS) return;
  const params = new URLSearchParams(location.search);
  const id = params.get('id') || window.AAZLI_PRODUCTS[0].id;
  const p = window.AAZLI_PRODUCTS.find(x => x.id === id) || window.AAZLI_PRODUCTS[0];

  document.title = p.name + ' — AAZLI Industries';
  el.dataset.categorySlug = p.categorySlug;
  el.dataset.productId = p.id;

  el.innerHTML = `
    <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">
      <div>
        <div class="aspect-[4/3] cut-card flex items-center justify-center" style="background:${p.swatch}">
          <span class="font-display text-white/90">${p.category}</span>
        </div>
        <div class="grid grid-cols-4 gap-3 mt-3">
          ${[1,2,3,4].map(() => `<div class="aspect-square border border-[var(--line)] flex items-center justify-center text-[10px] text-white/80 text-center px-1" style="background:${p.swatch}">Photo<br>coming soon</div>`).join('')}
        </div>
      </div>
      <div>
        <nav class="text-xs text-[var(--slate)] mb-4"><a href="products.html" class="hover:text-[var(--orange)]">Products</a> / <a href="products.html?category=${p.categorySlug}" class="hover:text-[var(--orange)]">${p.category}</a> / <span class="text-[var(--ink)]">${p.name}</span></nav>
        <span class="eyebrow">${p.category}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-bold mt-2 mb-4">${p.name}</h1>
        <p class="text-[var(--slate)] leading-relaxed mb-6">${p.description}</p>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
          <div class="p-4 border border-[var(--line)] bg-white"><span class="block text-xs font-semibold text-[var(--slate)] mb-1">MOQ</span>${p.moq}</div>
          <div class="p-4 border border-[var(--line)] bg-white"><span class="block text-xs font-semibold text-[var(--slate)] mb-1">Production Time</span>${p.leadTime}</div>
          <div class="p-4 border border-[var(--line)] bg-white"><span class="block text-xs font-semibold text-[var(--slate)] mb-1">Material</span>${p.material}</div>
          <div class="p-4 border border-[var(--line)] bg-white"><span class="block text-xs font-semibold text-[var(--slate)] mb-1">Sizes</span>${p.sizes}</div>
        </div>

        <div class="mb-6">
          <h3 class="font-display font-semibold mb-2">Key Features</h3>
          <ul class="space-y-1.5 text-sm text-[var(--slate)]">
            ${p.features.map(f => `<li class="flex gap-2"><span class="grad-text font-bold">&#10003;</span>${f}</li>`).join('')}
          </ul>
        </div>

        <div class="mb-8">
          <h3 class="font-display font-semibold mb-2">Customization Options</h3>
          <div class="flex flex-wrap gap-2">
            ${p.customization.map(c => `<span class="text-xs font-medium px-3 py-1.5 bg-[var(--paper)] border border-[var(--line)]">${c}</span>`).join('')}
          </div>
        </div>

        <div class="flex flex-wrap gap-3">
          <a href="request-quote.html?product=${encodeURIComponent(p.name)}" class="btn-grad">Request a Quote</a>
          <a href="contact.html" class="btn-outline">Ask About This Product</a>
        </div>
      </div>
    </div>
  `;
}

function renderRelatedProducts() {
  const el = document.getElementById('relatedProducts');
  const detail = document.getElementById('productDetail');
  if (!el || !detail || !window.AAZLI_PRODUCTS) return;
  const catSlug = detail.dataset.categorySlug;
  const id = detail.dataset.productId;
  const related = window.AAZLI_PRODUCTS.filter(p => p.categorySlug === catSlug && p.id !== id).slice(0, 3);
  el.innerHTML = related.map(p => productCard(p, true)).join('') || '';
  const wrap = document.getElementById('relatedProductsWrap');
  if (wrap && related.length === 0) wrap.classList.add('hidden');
}

function populateQuoteProductSelect() {
  const sel = document.getElementById('quoteProduct');
  if (!sel || !window.AAZLI_PRODUCTS) return;
  window.AAZLI_PRODUCTS.forEach(p => {
    const opt = document.createElement('option');
    opt.value = p.name; opt.textContent = p.name;
    sel.appendChild(opt);
  });
  const params = new URLSearchParams(location.search);
  const pre = params.get('product');
  if (pre) sel.value = pre;
}
