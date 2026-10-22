<?php
/** Auto-ported content body from legacy examinations.php */
?>
<?php
/**
 * examinations.php — Examinations & Assessments
 * Department of Medical Sciences - Riphah International University (Peshawar Campus)
 * Data source: https://prime.edu.pk/mbbs_apis/datesheet_mbbs.php
 */
$page_title       = 'Examinations & Assessments — Department of Medical Sciences - Riphah International University (Peshawar Campus)';
$page_description = 'View the latest MBBS datesheet, examinations schedule, and assessment details.';
$active_nav       = 'examinations.php';

$debug = isset($_GET['debug']) && $_GET['debug'] === '1';

$api_url        = 'https://prime.edu.pk/mbbs_apis/datesheet_mbbs.php';
$datesheet_data = [];
$raw_response   = '';
$fetch_error    = false;
$display_type   = ''; // 'json', 'html_table', 'html_list', 'raw'

// Fetch data (cURL + fallback)
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_CONNECTTIMEOUT => 8,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; PMC-Website/1.0)',
    CURLOPT_HTTPHEADER     => ['Accept: text/html,application/json'],
]);
$raw_response = curl_exec($ch);
$http_code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($raw_response === false || $http_code < 200 || $http_code >= 300) {
    $context = stream_context_create([
        'http' => ['method' => 'GET', 'header' => "Accept: text/html,application/json\r\nUser-Agent: Mozilla/5.0\r\n", 'timeout' => 15],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $raw_response = @file_get_contents($api_url, false, $context);
    if ($raw_response === false) {
        $fetch_error = true;
    }
}

if (!$fetch_error && $raw_response !== '') {
    // 1) Try JSON
    $data = json_decode($raw_response, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        $datesheet_data = $data;
        $display_type = 'json';
    } else {
        // 2) Try HTML parsing
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($raw_response, 'HTML-ENTITIES', 'UTF-8'));
        
        // Check for <table>
        $tables = $dom->getElementsByTagName('table');
        if ($tables->length > 0) {
            $extracted_table = $dom->saveHTML($tables->item(0));
            $display_type = 'html_table';
        } else {
            // Parse the API's <ul><li><a href="...">Title</a></li></ul>
            $lists = $dom->getElementsByTagName('ul');
            if ($lists->length > 0) {
                $list_items = [];
                foreach ($lists as $list) {
                    foreach ($list->getElementsByTagName('li') as $li) {
                        $anchors = $li->getElementsByTagName('a');
                        if ($anchors->length === 0) {
                            continue;
                        }
                        $anchor = $anchors->item(0);
                        $title = trim($anchor->textContent);
                        $url = trim($anchor->getAttribute('href'));
                        $parts = parse_url($url);
                        // Only accept links to the official datesheet host.
                        if ($title !== '' && is_array($parts)
                            && strtolower($parts['scheme'] ?? '') === 'https'
                            && strtolower($parts['host'] ?? '') === 'sas.prime.edu.pk') {
                            $list_items[] = ['title' => $title, 'url' => $url];
                        }
                    }
                }
                $display_type = $list_items ? 'html_list' : 'empty_list';
            } else {
                $display_type = 'raw'; // no table, no ul
            }
        }
    }
}


?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Examinations &amp; Assessments</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
      Education &amp; Research<span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Examinations</span>
    </div>
  </div>
</div>

<?php if ($debug): ?>
<section class="container mb-4">
  <div class="alert alert-secondary">
    <strong>DEBUG MODE</strong>
    <pre style="white-space:pre-wrap;font-size:.75rem;"><?= htmlspecialchars(print_r([
      'http_code'    => $http_code ?? 'unknown',
      'display_type' => $display_type,
      'raw_first_500' => substr($raw_response, 0, 500),
      'list_items_count' => isset($list_items) ? count($list_items) : 0
    ], true)) ?></pre>
  </div>
</section>
<?php endif; ?>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content fu">
          <h2 class="sec-title" style="font-size:1.8rem;">MBBS Datesheet</h2>
          <!--<p class="mb-4">-->
          <!--  The datesheet below is automatically updated from the official examination portal. All dates are subject to change.-->
          <!--</p>-->

          <?php if ($fetch_error): ?>
            <div class="alert alert-danger">
              <i class="bi bi-exclamation-triangle-fill"></i> Could not connect to the datesheet server. Please try again later.
            </div>

          <?php elseif ($display_type === 'json'): ?>
            <?php if (empty($datesheet_data)): ?>
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No upcoming datesheet entries have been published yet.
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle border">
                  <thead class="table-light">
                    <tr>
                      <th>Date</th><th>Day</th><th>Subject / Paper</th><th>Year / Class</th><th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($datesheet_data as $entry): ?>
                      <tr>
                        <td><?= htmlspecialchars($entry['exam_date'] ?? $entry['date'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($entry['day'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($entry['subject'] ?? $entry['paper'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($entry['year'] ?? $entry['class'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($entry['time'] ?? '—') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>

          <?php elseif ($display_type === 'html_table'): ?>
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> The datesheet below has been automatically formatted from the official source.
            </div>
            <div class="table-responsive border rounded-3 p-3 bg-white">
              <?= $extracted_table ?>
            </div>

          <?php elseif ($display_type === 'html_list'): ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle border">
                <thead class="table-light">
                  <tr>
                    <th style="width:75px;">S.No</th>
                    <th>Datesheet Title</th>
                    <th style="width:140px;">Attachment</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($list_items as $index => $item): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td>
                        <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="text-decoration-none fw-semibold">
                          <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                          <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                      </td>
                      <td>
                        <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-eye"></i> View PDF
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          <?php elseif ($display_type === 'empty_list' || ($display_type === 'raw' && trim(strip_tags($raw_response)) === '')): ?>
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> No upcoming datesheet entries have been published yet.
            </div>

          <?php else: ?>
            <!-- Raw fallback for any other content -->
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle"></i> The datesheet could not be displayed in a table format. Showing the official information below.
            </div>
            <div class="border p-4 rounded-3" style="background:#fff; max-height:600px; overflow-y:auto;">
              <?= nl2br(htmlspecialchars($raw_response)) ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?= $this->include('partials/sidebar') ?>
    </div>
  </div>
</section>



<style>
.table td, .table th { font-size: 0.9rem; vertical-align: middle; }
.table-responsive table { width: 100%; border-collapse: collapse; }
.table-responsive table td, .table-responsive table th { padding: 8px; border: 1px solid #dee2e6; }
</style>