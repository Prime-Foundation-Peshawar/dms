<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Examinations &amp; Assessments</h1>
    <div class="breadcrumb-pmc">
      <a href="./">Home</a><span class="sep"><i class="bi bi-chevron-right"></i></span>
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
            <!-- Convert list items to table – assume each <li> contains comma/tab separated values? We'll display them as a simple columned table. -->
            <?php
              // Attempt to parse each line: common format "Date - Subject - Year - Time"
              // If structure unknown, we'll present as single column table with full line
              $rows = [];
              foreach ($list_items as $line) {
                  // Try to split by common delimiters
                  $parts = preg_split('/\s{2,}|\t| – | - |,/', $line);
                  if (count($parts) >= 3) {
                      $rows[] = $parts;
                  } else {
                      $rows[] = [$line];
                  }
              }
              // Determine columns
              $max_cols = max(array_map('count', $rows));
              $headers = ['Date', 'Day', 'Subject', 'Year', 'Time'];
              // Use only as many headers as needed
              $headers = array_slice($headers, 0, $max_cols);
            ?>
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> The datesheet below has been automatically formatted from the official list.
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle border">
                <thead class="table-light">
                  <tr>
                    <?php foreach ($headers as $h): ?>
                      <th><?= $h ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rows as $row): ?>
                    <tr>
                      <?php foreach ($row as $cell): ?>
                        <td><?= htmlspecialchars($cell) ?></td>
                      <?php endforeach; ?>
                      <?php for ($i = count($row); $i < $max_cols; $i++): ?>
                        <td>—</td>
                      <?php endfor; ?>
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
      <?php include('includes/sidebar.php'); ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
