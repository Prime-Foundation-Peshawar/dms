<?php /* Shared listing filters: expects #eventsGrid, .ev-item[data-cat|data-year|data-date], #evSearch, #evYear, #evSort, #evNoResults, .cat-pill[data-cat]. */ ?>
<script>
  const catPills = document.querySelectorAll('.cat-pill[data-cat]');
  let activeCat = 'all';

  catPills.forEach(pill => {
    pill.addEventListener('click', e => {
      e.preventDefault();
      activeCat = pill.dataset.cat;
      catPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      applyFilters();
    });
  });

  function filterCat(cat) {
    activeCat = cat;
    catPills.forEach(p => p.classList.toggle('active', p.dataset.cat === cat));
    applyFilters();
    const grid = document.getElementById('eventsGrid');
    if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  document.querySelectorAll('[data-filter-cat]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      filterCat(link.getAttribute('data-filter-cat'));
    });
  });

  const evSearch = document.getElementById('evSearch');
  const evYear = document.getElementById('evYear');
  const evSort = document.getElementById('evSort');
  if (evSearch) evSearch.addEventListener('input', applyFilters);
  if (evYear) evYear.addEventListener('change', applyFilters);
  if (evSort) evSort.addEventListener('change', applyFilters);

  function applyFilters() {
    const query = (evSearch && evSearch.value.trim().toLowerCase()) || '';
    const year = (evYear && evYear.value) || '';
    const sort = (evSort && evSort.value) || 'newest';
    const grid = document.getElementById('eventsGrid');
    const items = [...document.querySelectorAll('.ev-item')];
    let visible = 0;

    items.sort((a, b) => {
      const da = a.dataset.date || '';
      const db = b.dataset.date || '';
      return sort === 'oldest' ? da.localeCompare(db) : db.localeCompare(da);
    }).forEach(item => {
      if (grid) grid.appendChild(item);
    });

    items.forEach(item => {
      const cat = item.dataset.cat;
      const itemYear = item.dataset.year;
      const text = item.innerText.toLowerCase();
      const matchCat = activeCat === 'all' || cat === activeCat;
      const matchYear = !year || itemYear === year;
      const matchQ = !query || text.includes(query);
      const show = matchCat && matchYear && matchQ;
      item.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    const empty = document.getElementById('evNoResults');
    if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
  }

  function resetFilters() {
    if (evSearch) evSearch.value = '';
    if (evYear) evYear.value = '';
    activeCat = 'all';
    catPills.forEach(p => p.classList.toggle('active', p.dataset.cat === 'all'));
    applyFilters();
  }
</script>
