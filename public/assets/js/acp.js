(function () {
  'use strict';

  document.querySelectorAll('.acp-fu').forEach(function (el, i) {
    setTimeout(function () { el.classList.add('is-in'); }, 40 + i * 45);
  });

  document.querySelectorAll('[data-acp-count]').forEach(function (el) {
    var target = parseInt(el.getAttribute('data-acp-count'), 10) || 0;
    var start = 0;
    var steps = 24;
    var step = 0;
    var tick = function () {
      step++;
      var t = step / steps;
      var eased = 1 - Math.pow(1 - t, 3);
      el.textContent = String(Math.round(start + (target - start) * eased));
      if (step < steps) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });

  document.querySelectorAll('[data-acp-filter]').forEach(function (input) {
    var tableSel = input.getAttribute('data-acp-filter');
    var table = document.querySelector(tableSel);
    if (!table) return;
    input.addEventListener('input', function () {
      var q = input.value.trim().toLowerCase();
      table.querySelectorAll('tbody tr').forEach(function (tr) {
        if (tr.classList.contains('acp-empty-row')) return;
        tr.style.display = !q || tr.textContent.toLowerCase().indexOf(q) !== -1 ? '' : 'none';
      });
    });
  });

  document.querySelectorAll('.acp-drop').forEach(function (zone) {
    var input = zone.querySelector('input[type=file]');
    if (!input) return;
    ['dragenter', 'dragover'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) {
        e.preventDefault();
        zone.classList.add('is-dragover');
      });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) {
        e.preventDefault();
        zone.classList.remove('is-dragover');
      });
    });
    zone.addEventListener('drop', function (e) {
      if (!e.dataTransfer || !e.dataTransfer.files.length) return;
      var dt = new DataTransfer();
      Array.prototype.forEach.call(e.dataTransfer.files, function (f) { dt.items.add(f); });
      input.files = dt.files;
      var label = zone.querySelector('[data-drop-label]');
      if (label) {
        label.textContent = input.files.length > 1
          ? input.files.length + ' files selected'
          : (input.files[0] && input.files[0].name) || '1 file selected';
      }
    });
    input.addEventListener('change', function () {
      var label = zone.querySelector('[data-drop-label]');
      if (!label) return;
      if (!input.files || !input.files.length) {
        label.textContent = 'Drop files here or click to browse';
        return;
      }
      label.textContent = input.files.length > 1
        ? input.files.length + ' files selected'
        : input.files[0].name;
    });
  });

  function csrfToken() {
    var any = document.querySelector('input[type=hidden][name*="csrf"]');
    return any ? { name: any.name, value: any.value } : null;
  }

  function bindSortable(root) {
    if (!root || typeof Sortable === 'undefined') return;
    var url = root.getAttribute('data-reorder-url');
    Sortable.create(root, {
      handle: '.acp-sort-handle',
      animation: 160,
      ghostClass: 'sortable-ghost',
      onEnd: function () {
        if (!url) return;
        var ids = Array.prototype.map.call(root.querySelectorAll('[data-id]'), function (el) {
          return el.getAttribute('data-id');
        });
        var body = new FormData();
        ids.forEach(function (id) { body.append('ids[]', id); });
        var tok = csrfToken();
        if (tok) body.append(tok.name, tok.value);
        fetch(url, { method: 'POST', body: body, credentials: 'same-origin' })
          .catch(function () { /* silent */ });
      }
    });
  }

  document.querySelectorAll('[data-acp-sortable]').forEach(bindSortable);

  window.acpInitTinyMCE = function (selector) {
    if (typeof tinymce === 'undefined') return;
    tinymce.init({
      selector: selector || 'textarea.acp-tinymce',
      height: 380,
      menubar: 'file edit view insert format tools table',
      plugins: 'lists link image table code',
      toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | removeformat | code',
      branding: false,
      promotion: false,
      convert_urls: false,
      content_style: 'body{font-family:Quicksand,sans-serif;font-size:15px;color:#152636;line-height:1.55}',
      setup: function (editor) {
        editor.on('change keyup', function () { editor.save(); });
      }
    });
    document.querySelectorAll('form').forEach(function (form) {
      if (form.dataset.tinymceBound) return;
      form.dataset.tinymceBound = '1';
      form.addEventListener('submit', function () {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
        form.querySelectorAll('textarea.acp-tinymce').forEach(function (ta) {
          var textName = ta.getAttribute('data-text-name');
          if (!textName) return;
          var hidden = form.querySelector('[name="' + textName + '"]');
          if (!hidden) return;
          var tmp = document.createElement('div');
          tmp.innerHTML = ta.value || '';
          hidden.value = (tmp.innerText || tmp.textContent || '').trim();
        });
      });
    });
  };

  if (document.querySelector('textarea.acp-tinymce')) {
    window.acpInitTinyMCE();
  }
})();
