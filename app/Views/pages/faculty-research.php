<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ═══ HERO ═══ -->
<section class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Faculty Research</h1>
    <nav class="breadcrumb-pmc" aria-label="breadcrumb">
      <a href="./">Home</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="medical-education">Education &amp; Research</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Faculty Research</span>
    </nav>
  </div>
</section>

<?php if ($debug_mode): ?>
<section class="container">
  <div class="alert alert-secondary">
    <strong>DEBUG MODE</strong> — remove <code>?debug=1</code> handling before launch.
    <pre style="white-space:pre-wrap;font-size:.75rem;"><?= htmlspecialchars(print_r($debug_info, true)) ?></pre>
  </div>
</section>
<?php endif; ?>

<!-- ═══ MAIN CONTENT ═══ -->
<section class="pmc-section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="sec-title" style="font-size:1.8rem;">Faculty Research Publications</h2>
        <p class="mb-4">
          The faculty of the <strong>Department of Medical Sciences – Riphah International University (Peshawar Campus)</strong>
          have an outstanding record of research contributions. Below is the complete list of publications, dynamically sourced from the ORIC research database.
        </p>

        <?php if ($fetch_error): ?>
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Unable to load publication data at the moment. Please try again later or contact <a href="contact">our support team</a>.
          </div>
        <?php elseif (empty($flat_publications)): ?>
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No publications found in the database.
          </div>
        <?php else: ?>
          <!-- Filter controls -->
          <div class="row mb-4">
            <div class="col-md-4">
              <label for="deptFilter" class="form-label fw-bold">Department</label>
              <select id="deptFilter" class="form-select">
                <option value="">All Departments</option>
                <?php foreach ($departments as $dept): 
                  if(!empty($dept)):?>
                  <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                <?php endif; endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="yearFilter" class="form-label fw-bold">Year</label>
              <select id="yearFilter" class="form-select">
                <option value="">All Years</option>
                <?php foreach ($years as $yr): ?>
                  <option value="<?= htmlspecialchars($yr) ?>"><?= htmlspecialchars($yr) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button id="resetFilters" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i> Reset Filters</button>
            </div>
          </div>

          <!-- DataTable -->
          <div class="table-responsive">
            <table id="publicationsTable" class="table table-striped table-hover align-middle border" style="width:100%;">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Author</th>
                  <th>Publication</th>
                  <th>Journal</th>
                  <th>Year</th>
                  <th class="d-none">Department</th> <!-- hidden column for filtering -->
                </tr>
              </thead>
              <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($flat_publications as $pub): ?>
                  <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($pub['author']) ?></td>
                    <td><?= htmlspecialchars($pub['title']) ?></td>
                    <td><?= htmlspecialchars($pub['journal']) ?: '<span class="text-muted">—</span>' ?></td>
                    <td><?= htmlspecialchars($pub['year']) ?></td>
                    <td class="d-none"><?= htmlspecialchars($pub['department']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Include jQuery (required by DataTables) and DataTables Bootstrap 5 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#publicationsTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[4, 'desc']],        // sort by Year column (index 4) descending
        "columnDefs": [
            { "targets": 5, "visible": false }   // hide Department column
        ],
        "language": {
            "search": "Search all columns:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ publications",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });

    // Department filter
    $('#deptFilter').on('change', function () {
        var dept = $(this).val();
        // regex exact match on column 5 (department)
        table.column(5).search(dept ? '^'+$.fn.dataTable.util.escapeRegex(dept)+'$' : '', true, false).draw();
    });

    // Year filter
    $('#yearFilter').on('change', function () {
        var year = $(this).val();
        // year column is index 4 (visible)
        table.column(4).search(year ? '^'+$.fn.dataTable.util.escapeRegex(year)+'$' : '', true, false).draw();
    });

    // Reset all filters
    $('#resetFilters').on('click', function () {
        $('#deptFilter, #yearFilter').val('');
        table.search('').columns().search('').draw();
    });
});
</script>

<style>
.btn-outline-teal {
  color: var(--teal);
  border-color: var(--teal);
}
.btn-outline-teal:hover {
  background-color: var(--teal);
  color: white;
}
.table td, .table th {
  font-size: 0.85rem;
  vertical-align: middle;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 15px;
}
</style>

<?= $this->endSection() ?>
