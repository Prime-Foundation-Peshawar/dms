<?php
/**
 * faculty-research.php — Faculty Research Publications (DataTable version)
 * Department of Medical Sciences - Riphah International University (Peshawar Campus)
 * Data source: ORIC API
 */
$page_title       = 'Faculty Research — Department of Medical Sciences - Riphah International University (Peshawar Campus)';
$page_description = 'Explore the extensive research publications by faculty members of the Department of Medical Sciences, covering medicine, surgery, basic sciences, dentistry, and allied health disciplines.';
$active_nav       = 'faculty-research.php';

$debug_mode = isset($_GET['debug']) && $_GET['debug'] === '1';

// ── Fetch publication data from ORIC API ─────────────────
$api_url = 'http://oric.prime.edu.pk/apis/getPublicationsInfo.php';

$publications_by_dept = [];
$fetch_error   = false;
$debug_info    = [];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_CONNECTTIMEOUT => 8,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 3,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; PMC-Website/1.0)',
    CURLOPT_HTTPHEADER     => ['Accept: application/json'],
]);
$result     = curl_exec($ch);
$http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

$debug_info['http_code']  = $http_code;
$debug_info['curl_error'] = $curl_error;
$debug_info['raw_result'] = $result;

if ($result === false || $curl_error) {
    $fetch_error = true;
    error_log('[faculty-research.php] cURL failed: ' . $curl_error);
} elseif ($http_code < 200 || $http_code >= 300) {
    $fetch_error = true;
    error_log('[faculty-research.php] API returned HTTP ' . $http_code);
} else {
    $data = json_decode($result);
    $json_error = json_last_error();

    if ($json_error !== JSON_ERROR_NONE) {
        $fetch_error = true;
        error_log('[faculty-research.php] JSON decode error: ' . json_last_error_msg());
    } else {
        $list = null;
        if (is_array($data)) {
            $list = $data;
        } elseif (is_object($data)) {
            foreach (['data', 'records', 'result', 'publications', 'items'] as $key) {
                if (isset($data->$key) && is_array($data->$key)) {
                    $list = $data->$key;
                    break;
                }
            }
        }

        if ($list === null) {
            $fetch_error = true;
            error_log('[faculty-research.php] Unexpected API response shape.');
        } else {
            foreach ($list as $pub) {
                $dept = $pub->depName ?? 'Other';
                $publications_by_dept[$dept][] = $pub;
            }
        }
    }
}

// Flatten all publications into a single array for DataTable
$flat_publications = [];
foreach ($publications_by_dept as $dept => $pubs) {
    foreach ($pubs as $pub) {
        $flat_publications[] = [
            'department' => $dept,
            'author'     => $pub->authorName ?? '',
            'title'      => $pub->pubTitle ?? '',
            'journal'    => $pub->pubJournalName ?? '',
            'year'       => $pub->pubYear ?? ''
        ];
    }
}

// Sort for consistent initial display (year desc, then department)
usort($flat_publications, function($a, $b) {
    if ((int)$a['year'] !== (int)$b['year']) return (int)$b['year'] - (int)$a['year'];
    return strcmp($a['department'], $b['department']);
});

// Collect unique departments & years for filter dropdowns
$departments = array_keys($publications_by_dept);
sort($departments);
$years = array_values(array_unique(array_map(function($p){ return $p['year']; }, $flat_publications)));
rsort($years); // newest first

include('includes/header.php');
?>

<!-- ═══ HERO ═══ -->
<section class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Faculty Research</h1>
    <nav class="breadcrumb-pmc" aria-label="breadcrumb">
      <a href="index.php">Home</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="medical-education.php">Education &amp; Research</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
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
            Unable to load publication data at the moment. Please try again later or contact <a href="contact.php">our support team</a>.
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

<?php include('includes/footer.php'); ?>