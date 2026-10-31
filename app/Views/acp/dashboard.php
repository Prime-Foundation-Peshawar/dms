<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$kpis = $kpis ?? [];
$charts = $charts ?? [];
$stats = $stats ?? [];
$brand = ['#155F7A', '#C4873A', '#2A87A8', '#0B1F33', '#E0A85C', '#6e8294', '#143452', '#8a5a18'];

$fmtWhen = static function (?string $raw): string {
    $raw = trim((string) $raw);
    if ($raw === '') {
        return '';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return $raw;
    }
    $diff = time() - $ts;
    if ($diff >= 0 && $diff < 86400) {
        return 'Today';
    }
    if ($diff < 0 && $diff > -86400) {
        return 'Tomorrow';
    }
    if ($diff > 0 && $diff < 7 * 86400) {
        return (int) floor($diff / 86400) . 'd ago';
    }
    if ($diff < 0 && $diff > -7 * 86400) {
        return 'in ' . (int) ceil(abs($diff) / 86400) . 'd';
    }

    return date('d M Y', $ts);
};
?>
<div class="acp-page-head acp-fu acp-dash-head">
  <div>
    <h1 class="acp-dash-title">Dashboard</h1>
    <p class="mb-0 text-muted small"><?= esc(date('l, d F Y')) ?> · CMS overview</p>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <?php if (acp_can_open('acp/news/create')): ?>
    <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/news/create') ?>"><i class="bi bi-plus-lg"></i> News or Event</a>
    <?php endif; ?>
    <?php if (acp_can_open('acp/reports')): ?>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/reports') ?>"><i class="bi bi-graph-up-arrow"></i> Reports</a>
    <?php endif; ?>
    <?php if (acp_can_open('acp/departments')): ?>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/departments') ?>"><i class="bi bi-building"></i> Departments</a>
    <?php endif; ?>
  </div>
</div>

<?php if (! empty($report)): ?>
<?php
$rp = $report;
$reportLink = static fn (string $type) => site_url('acp/reports') . '?period=' . $type;
?>
<div class="acp-card acp-dash-panel mb-3 acp-fu">
  <div class="acp-dash-card-head">
    <div>
      <div class="acp-form-section-title mb-0">Reports · <?= esc($rp['period']['range_label']) ?></div>
      <p class="small text-muted mb-0">
        <?= (int) $rp['totals']['reporting'] ?> of <?= (int) $rp['totals']['departments'] ?> departments sent data
        · <?= count($rp['missing']['no_activity']) ?> sent no activity
        · <?= number_format((int) $rp['totals']['faculty_missing']) ?> faculty profiles missing
        · <?= count($rp['upcoming']) ?> upcoming events
      </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a class="btn-acp btn-acp-sm btn-acp-primary" href="<?= $reportLink('month') ?>">Monthly</a>
      <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= $reportLink('quarter') ?>">Quarterly</a>
      <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= $reportLink('year') ?>">Annual</a>
      <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= $reportLink('range') ?>">Date range</a>
    </div>
  </div>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="acp-dash-attn-label"><i class="bi bi-trophy"></i> Top contributors</div>
      <?php if (! $rp['tiers']['top']): ?>
        <div class="acp-empty py-2">No data received yet this month.</div>
      <?php else: ?>
        <ol class="acp-tier-list mb-0">
          <?php foreach (array_slice($rp['tiers']['top'], 0, 3) as $d): ?>
            <li><span><?= esc($d['name']) ?></span><strong><?= (int) $d['contributions'] ?></strong></li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <div class="acp-dash-attn-label"><i class="bi bi-arrow-down-circle"></i> Low contributors <span class="text-muted fw-normal">(<?= count($rp['tiers']['low']) ?>)</span></div>
      <?php if (! $rp['tiers']['low']): ?>
        <div class="acp-empty py-2">No low contributors.</div>
      <?php else: ?>
        <ol class="acp-tier-list mb-0">
          <?php foreach (array_slice(array_reverse($rp['tiers']['low']), 0, 3) as $d): ?>
            <li><span><?= esc($d['name']) ?></span><strong><?= (int) $d['contributions'] ?></strong></li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <div class="acp-dash-attn-label"><i class="bi bi-lightbulb"></i> Suggestions</div>
      <ul class="acp-suggest mb-0">
        <?php foreach (array_slice($rp['suggestions'], 0, 2) as $sg): ?>
          <li class="tone-<?= esc($sg['tone']) ?>"><span><?= esc($sg['text']) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row g-3 mb-3 acp-kpi-row">
  <?php foreach ($kpis as $kpi): ?>
  <div class="col-6 col-md-4 col-xl-3 acp-fu">
    <?= acp_link_open($kpi['href'], 'acp-kpi tone-' . ($kpi['tone'] ?? 'teal')) ?>
      <div class="acp-kpi-top">
        <span class="lbl"><?= esc($kpi['label'] ?? '') ?></span>
        <i class="bi <?= esc($kpi['icon'] ?? 'bi-circle') ?>"></i>
      </div>
      <div class="num" data-acp-count="<?= (int) $kpi['total'] ?>">0</div>
      <div class="sub"><?= esc($kpi['sub']) ?></div>
    <?= acp_link_close($kpi['href']) ?>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-8 acp-fu">
    <div class="acp-card acp-chart-card acp-chart-card-wide">
      <div class="acp-dash-card-head">
        <div>
          <div class="acp-form-section-title mb-0">Publishing activity</div>
          <p class="small text-muted mb-0">Last 12 months</p>
        </div>
      </div>
      <div class="acp-chart-wrap">
        <canvas id="chartMonthly"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4 acp-fu">
    <div class="acp-card acp-chart-card">
      <div class="acp-dash-card-head">
        <div>
          <div class="acp-form-section-title mb-0">Faculty health</div>
          <p class="small text-muted mb-0"><?= (int) ($stats['facultyComplete'] ?? 0) ?> complete profiles</p>
        </div>
      </div>
      <div class="acp-chart-wrap acp-chart-wrap-sm">
        <canvas id="chartFacultyHealth"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-4 acp-fu">
    <div class="acp-card acp-chart-card">
      <div class="acp-form-section-title">News status</div>
      <div class="acp-chart-wrap acp-chart-wrap-sm">
        <canvas id="chartNewsStatus"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 acp-fu">
    <div class="acp-card acp-chart-card">
      <div class="acp-form-section-title">Pages source</div>
      <div class="acp-chart-wrap acp-chart-wrap-sm">
        <canvas id="chartPagesSource"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 acp-fu">
    <div class="acp-card acp-chart-card">
      <div class="acp-form-section-title">Departments by group</div>
      <div class="acp-chart-wrap acp-chart-wrap-sm">
        <canvas id="chartDeptGroups"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-5 acp-fu">
    <div class="acp-card acp-chart-card">
      <div class="acp-dash-card-head">
        <div class="acp-form-section-title mb-0">Profiles by department</div>
        <?php if (acp_can_open('acp/faculty')): ?>
        <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty') ?>">All</a>
        <?php endif; ?>
      </div>
      <div class="acp-chart-wrap">
        <canvas id="chartFacultyDepts"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-7 acp-fu">
    <div class="acp-card acp-dash-panel h-100">
      <div class="acp-dash-card-head">
        <div>
          <div class="acp-form-section-title mb-0">Needs attention</div>
          <p class="small text-muted mb-0">
            <?= (int) ($stats['facultyMissingPhoto'] ?? 0) ?> missing photo
            · <?= (int) ($stats['facultyMissingEmp'] ?? 0) ?> CV-only
            <?php if (! empty($stats['newsDraft'])): ?>
              · <?= (int) $stats['newsDraft'] ?> draft news
            <?php endif; ?>
          </p>
        </div>
        <?php if (acp_can_open('acp/faculty')): ?>
        <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty') ?>">Faculty</a>
        <?php endif; ?>
      </div>

      <div class="acp-dash-attn">
        <div class="acp-dash-attn-col">
          <div class="acp-dash-attn-label"><i class="bi bi-image"></i> Missing photo</div>
          <?php if (empty($attentionMissingPhoto)): ?>
            <div class="acp-empty py-3">All profiles have photos.</div>
          <?php else: ?>
            <ul class="acp-feed mb-0">
              <?php foreach ($attentionMissingPhoto as $f): ?>
                <li>
                  <?= acp_link_open('acp/faculty/edit/' . $f['id']) ?>
                    <span class="acp-feed-title"><?= esc($f['name']) ?></span>
                    <span class="acp-feed-meta">
                      <?= esc($f['department_name'] ?: '—') ?>
                      · <?= trim((string) ($f['emp_id'] ?? '')) !== '' ? 'empId ' . esc($f['emp_id']) : 'CV-only' ?>
                    </span>
                  <?= acp_link_close('acp/faculty/edit/' . $f['id']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
        <div class="acp-dash-attn-col">
          <div class="acp-dash-attn-label"><i class="bi bi-person-vcard"></i> CV-only <span class="text-muted fw-normal">(not in HRMS)</span></div>
          <?php if (empty($attentionCvOnly)): ?>
            <div class="acp-empty py-3">Every profile has an empId.</div>
          <?php else: ?>
            <ul class="acp-feed mb-0">
              <?php foreach ($attentionCvOnly as $f): ?>
                <li>
                  <?= acp_link_open('acp/faculty/edit/' . $f['id']) ?>
                    <span class="acp-feed-title"><?= esc($f['name']) ?></span>
                    <span class="acp-feed-meta"><?= esc($f['department_name'] ?: '—') ?></span>
                  <?= acp_link_close('acp/faculty/edit/' . $f['id']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (! empty($draftNews)): ?>
            <div class="acp-dash-attn-label mt-3"><i class="bi bi-pencil-square"></i> Draft news</div>
            <ul class="acp-feed mb-0">
              <?php foreach ($draftNews as $n): ?>
                <li>
                  <?= acp_link_open('acp/news/edit/' . $n['id']) ?>
                    <span class="acp-feed-title"><?= esc($n['title']) ?></span>
                    <span class="acp-feed-meta">Draft</span>
                  <?= acp_link_close('acp/news/edit/' . $n['id']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 acp-dash-feeds">
  <div class="col-lg-6 acp-fu">
    <div class="acp-card acp-dash-panel h-100">
      <div class="acp-dash-card-head">
        <div class="acp-form-section-title mb-0">Recent news &amp; events</div>
        <?php if (acp_can_open('acp/news')): ?>
        <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/news') ?>">View all</a>
        <?php endif; ?>
      </div>
      <?php if (empty($recentNews)): ?>
        <div class="acp-empty">Nothing yet.</div>
      <?php else: ?>
        <ul class="acp-feed acp-feed-scroll mb-0">
          <?php foreach ($recentNews as $n): ?>
            <li>
              <?= acp_link_open('acp/news/edit/' . $n['id']) ?>
                <span class="acp-feed-title"><?= esc($n['title']) ?></span>
                <span class="acp-feed-meta"><?= esc($fmtWhen($n['published_at'] ?? null)) ?></span>
              <?= acp_link_close('acp/news/edit/' . $n['id']) ?>
              <span class="acp-badge <?= ($n['status'] ?? '') === 'published' ? 'acp-badge-ok' : 'acp-badge-muted' ?>"><?= esc($n['status'] ?? '') ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-6 acp-fu">
    <div class="acp-card acp-dash-panel h-100">
      <div class="acp-dash-card-head">
        <div class="acp-form-section-title mb-0">Upcoming events</div>
        <?php if (acp_can_open('acp/news')): ?>
        <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/news') ?>">View all</a>
        <?php endif; ?>
      </div>
      <?php if (empty($upcomingEvents)): ?>
        <div class="acp-empty">No upcoming events.</div>
      <?php else: ?>
        <ul class="acp-feed acp-feed-scroll mb-0">
          <?php foreach ($upcomingEvents as $e): ?>
            <li>
              <?= acp_link_open('acp/news/edit/' . $e['id']) ?>
                <span class="acp-feed-title"><?= esc($e['title']) ?></span>
                <span class="acp-feed-meta"><?= esc($fmtWhen($e['event_date'] ?? null)) ?></span>
              <?= acp_link_close('acp/news/edit/' . $e['id']) ?>
              <span class="acp-badge <?= ($e['status'] ?? '') === 'published' ? 'acp-badge-ok' : 'acp-badge-muted' ?>"><?= esc($e['status'] ?? '') ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
<script>
(function () {
  const brand = <?= json_encode($brand) ?>;
  const charts = <?= json_encode($charts, JSON_UNESCAPED_UNICODE) ?>;
  const font = { family: 'Quicksand', size: 11 };
  Chart.defaults.font.family = 'Quicksand';
  Chart.defaults.color = '#6e8294';

  const doughnut = (id, pack, cutout) => {
    const el = document.getElementById(id);
    if (!el || !pack) return;
    const data = pack.data || [];
    const empty = data.every((n) => !n);
    new Chart(el, {
      type: 'doughnut',
      data: {
        labels: pack.labels || [],
        datasets: [{
          data: empty ? [1] : data,
          backgroundColor: empty ? ['#e8eef3'] : brand,
          borderWidth: 0,
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { boxWidth: 10, boxHeight: 10, padding: 12, font, filter: () => !empty }
          },
          tooltip: { enabled: !empty }
        },
        cutout: cutout || '62%'
      }
    });
  };

  doughnut('chartNewsStatus', charts.newsStatus);
  doughnut('chartPagesSource', charts.pagesSource);
  doughnut('chartFacultyHealth', charts.facultyHealth, '68%');

  const deptEl = document.getElementById('chartDeptGroups');
  if (deptEl && charts.deptGroups) {
    new Chart(deptEl, {
      type: 'bar',
      data: {
        labels: charts.deptGroups.labels,
        datasets: [{
          label: 'Departments',
          data: charts.deptGroups.data,
          backgroundColor: brand[0],
          borderRadius: 8,
          maxBarThickness: 36
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { font } },
          y: { beginAtZero: true, ticks: { precision: 0, font }, grid: { color: 'rgba(213,224,232,.7)' } }
        }
      }
    });
  }

  const facEl = document.getElementById('chartFacultyDepts');
  if (facEl && charts.facultyDepts) {
    new Chart(facEl, {
      type: 'bar',
      data: {
        labels: charts.facultyDepts.labels,
        datasets: [{
          label: 'Profiles',
          data: charts.facultyDepts.data,
          backgroundColor: brand[1],
          borderRadius: 8,
          maxBarThickness: 22
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { beginAtZero: true, ticks: { precision: 0, font }, grid: { color: 'rgba(213,224,232,.7)' } },
          y: { grid: { display: false }, ticks: { font: { ...font, size: 10 } } }
        }
      }
    });
  }

  const monthEl = document.getElementById('chartMonthly');
  if (monthEl && charts.months) {
    new Chart(monthEl, {
      type: 'line',
      data: {
        labels: charts.months.labels,
        datasets: [
          {
            label: 'News & events',
            data: charts.months.news,
            borderColor: brand[0],
            backgroundColor: 'rgba(21,95,122,.14)',
            fill: true,
            tension: .35,
            pointRadius: 3,
            pointHoverRadius: 5
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, padding: 14, font } }
        },
        scales: {
          x: { grid: { display: false }, ticks: { font, maxRotation: 0 } },
          y: { beginAtZero: true, ticks: { precision: 0, font }, grid: { color: 'rgba(213,224,232,.7)' } }
        }
      }
    });
  }
})();
</script>
<?= $this->endSection() ?>
