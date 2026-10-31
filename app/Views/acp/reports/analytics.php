<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php

use App\Libraries\GoogleAnalytics as GA;

$typeLabels = ['month' => 'Monthly', 'quarter' => 'Quarterly', 'year' => 'Annual', 'range' => 'Custom'];
$range      = $period['range_label'];
$prevRange  = date('j M Y', strtotime($period['prev_start'])) . ' to ' . date('j M Y', strtotime($period['prev_last']));
$ok         = ! empty($data['ok']);
$notKnown   = static fn (string $v) => in_array($v, ['', '(not set)', '(data not available)', '(other)', 'unknown'], true) ? 'Not known' : $v;
$named      = static fn (array $map) => static fn (string $v) => $map[$v] ?? $notKnown($v);

$duration = static function (float $seconds): string {
    $s = (int) round($seconds);

    return $s < 60 ? $s . ' sec' : floor($s / 60) . ' min ' . ($s % 60) . ' sec';
};

// Plain sentence comparing two counts, e.g. "120 more than before".
$change = static function (float $now, float $before): string {
    $diff = (int) round($now - $before);
    if ($before == 0 && $now == 0) {
        return 'No change';
    }

    return $diff === 0 ? 'Same as before' : number_format(abs($diff)) . ($diff > 0 ? ' more' : ' fewer') . ' than before';
};

/**
 * A ranked list with a bar behind each count, so nothing needs a legend.
 *
 * @param list<array<string,mixed>> $rows
 */
$rankList = static function (array $rows, string $dim, string $metric, callable $name, string $unit): string {
    if (! $rows) {
        return '<div class="acp-empty py-2">No data for this period.</div>';
    }
    $max = max(array_column($rows, $metric)) ?: 1;
    $out = '<ul class="acp-ga-list">';
    foreach ($rows as $row) {
        $label = $name((string) $row[$dim]);
        $out .= '<li title="' . esc($label, 'attr') . '"><span class="acp-ga-bar" style="width:' . round($row[$metric] / $max * 100, 1) . '%"></span>'
            . '<span class="acp-ga-name">' . esc($label) . '</span>'
            . '<span class="acp-ga-num">' . number_format((int) $row[$metric]) . ' <small>' . esc($unit) . '</small></span></li>';
    }

    return $out . '</ul>';
};

$panel = static function (string $title, string $tip, string $icon, string $body, string $example = ''): string {
    return '<div class="col-md-6 col-xl-4"><div class="acp-card acp-dash-panel h-100">'
        . '<div class="acp-form-section-title" ' . acp_tip_attrs($title, $tip, $icon, $example) . '><i class="bi ' . esc($icon, 'attr') . '"></i> ' . esc($title) . '</div>'
        . $body . '</div></div>';
};
?>
<div class="acp-page-head acp-dash-head acp-report-head">
  <div>
    <h1 class="acp-dash-title">Website Analytics · <?= esc($typeLabels[$period['type']]) ?> Report</h1>
    <p class="acp-report-range-title"><i class="bi bi-calendar3"></i> Data from <?= esc($range) ?></p>
  </div>
  <div class="d-flex gap-2 flex-wrap acp-no-print">
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/reports') ?>"><i class="bi bi-arrow-left-short"></i> Back to Reports</a>
    <button type="button" class="btn-acp btn-acp-outline" onclick="window.print()"><i class="bi bi-printer"></i> Print / PDF</button>
  </div>
</div>

<?= view('acp/reports/_filter', ['period' => $period, 'options' => $options, 'action' => 'acp/website-analytics']) ?>

<?php if (! $connected): ?>
  <div class="acp-card acp-dash-panel mb-3">
    <div class="acp-form-section-title"><i class="bi bi-plug"></i> Google Analytics is not connected yet</div>
    <?php if (acp_is_view_only()): ?>
      <p class="mb-0">The IT team needs to connect Google Analytics before this page can show website figures.</p>
    <?php else: ?>
      <p>This page reads its figures directly from Google Analytics. To connect it, do the following once:</p>
      <ol class="mb-0">
        <li>In Google Cloud, turn on the <strong>Google Analytics Data API</strong> and create a <strong>service account</strong>. Download its JSON key.</li>
        <li>In Google Analytics, go to Admin → Property access management and add the service account's email address as a <strong>Viewer</strong>.</li>
        <li>In Google Analytics, go to Admin → Property details and copy the <strong>Property ID</strong> (only numbers, not the G-KLGCNTBPXD code).</li>
        <li>Save the key on the server as <code>writable/ga4-service-account.json</code>, and add to <code>.env</code>:
          <pre class="mt-2 mb-0"><code>ga4.propertyId = 123456789
ga4.credentialsFile = writable/ga4-service-account.json</code></pre>
        </li>
      </ol>
    <?php endif; ?>
  </div>
<?php elseif (! $ok): ?>
  <div class="acp-flash acp-flash-error">
    Google Analytics could not be loaded right now. <?= acp_is_view_only() ? 'Please try again later.' : esc((string) ($data['error'] ?? '')) ?>
  </div>
<?php else:
  $t = $data['totals'];
  $p = $data['prev'];
  $leftQuickly     = max(0, $t['sessions'] - $t['engagedSessions']);
  $leftQuicklyPrev = max(0, $p['sessions'] - $p['engagedSessions']);
?>
  <?php if (! empty($data['stale'])): ?>
    <div class="acp-flash acp-flash-error">Google Analytics could not be reached, so this shows the last saved figures from <?= esc(date('d M Y, H:i', (int) $data['fetched_at'])) ?>.</div>
  <?php endif; ?>
  <p class="small text-muted"<?= acp_tip_attrs('Comparing with Before', "\"Than before\" compares with {$prevRange}, the same length of time just before this period.", 'bi-arrow-left-right') ?>>
    Compared with <?= esc($prevRange) ?> · Figures refreshed <?= esc(date('d M Y, H:i', (int) $data['fetched_at'])) ?>
  </p>

  <div class="row g-3 mb-3 acp-kpi-row">
    <?php foreach ([
        ['teal', 'People Who Visited', 'bi-people', number_format((int) $t['activeUsers']), $change($t['activeUsers'], $p['activeUsers']),
            ['People Who Visited', 'Different people who came to the website. Google calls them "active users".', 'Someone who came three times counts as one person.']],
        ['navy', 'First-Time Visitors', 'bi-person-plus', number_format((int) $t['newUsers']), $change($t['newUsers'], $p['newUsers']),
            ['First-Time Visitors', 'People who came to the website for the first time in this period. Google calls them "new users".']],
        ['gold', 'Total Visits', 'bi-door-open', number_format((int) $t['sessions']), $change($t['sessions'], $p['sessions']),
            ['Total Visits', 'Each time someone came to the website. Google calls this a "session". A visit ends after 30 minutes of doing nothing.', 'Someone who came three times counts as three visits.']],
        ['teal', 'Pages Opened', 'bi-file-earmark-text', number_format((int) $t['screenPageViews']), $change($t['screenPageViews'], $p['screenPageViews']),
            ['Pages Opened', 'How many pages were opened in total. Google calls these "page views". Opening the same page twice counts as two.']],
        ['navy', 'Visits Where People Stayed', 'bi-hand-thumbs-up', number_format((int) $t['engagedSessions']), $change($t['engagedSessions'], $p['engagedSessions']),
            ['Visits Where People Stayed', 'Visits where the person stayed at least 10 seconds or opened two or more pages. Google calls these "engaged sessions".', 'More of these means people find the website useful.']],
        ['gold', 'Visits Where People Left Quickly', 'bi-box-arrow-right', number_format((int) $leftQuickly), $change($leftQuickly, $leftQuicklyPrev),
            ['Visits Where People Left Quickly', 'Visits that ended in under 10 seconds with only one page opened. Google calls this a "bounce".', 'Fewer is better.']],
        ['teal', 'Average Time per Visit', 'bi-stopwatch', esc($duration($t['averageSessionDuration'])), 'Before: ' . $duration($p['averageSessionDuration']),
            ['Average Time per Visit', 'How long a typical visit lasted, from opening the website to leaving it.']],
        ['navy', 'Pages per Visit', 'bi-layers', number_format($t['screenPageViewsPerSession'], 1), 'Before: ' . number_format($p['screenPageViewsPerSession'], 1),
            ['Pages per Visit', 'On average, how many pages someone opened during one visit.']],
    ] as [$tone, $lbl, $icon, $num, $sub, $tip]): ?>
      <div class="col-6 col-md-4 col-xl-3">
        <?= view('acp/reports/_kpi', ['tone' => $tone, 'label' => $lbl, 'icon' => $icon, 'num' => $num, 'sub' => $sub, 'tip' => $tip]) ?>
      </div>
    <?php endforeach; ?>
    <?php if ($liveNow !== null): ?>
      <div class="col-6 col-md-4 col-xl-3">
        <?= view('acp/reports/_kpi', ['tone' => 'gold', 'label' => 'On the Website Right Now', 'icon' => 'bi-broadcast', 'num' => number_format($liveNow), 'sub' => 'in the last 30 minutes',
            'tip' => ['On the Website Right Now', 'People who used the website in the last 30 minutes. This does not change with the period above.']]) ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="acp-card acp-chart-card mb-3">
    <div class="acp-dash-card-head">
      <div<?= acp_tip_attrs('Website Visits Over Time', 'How the number of people, visits and pages opened went up and down. Hover over the chart to read the exact numbers for each point.', 'bi-graph-up') ?>>
        <div class="acp-form-section-title mb-0">Website Visits Over Time</div>
        <p class="small text-muted mb-0"><?= esc($range) ?></p>
      </div>
    </div>
    <div class="acp-chart-wrap"><canvas id="chartVisits" role="img" aria-label="People, visits and pages opened over the selected period."></canvas></div>
  </div>

  <div class="acp-card acp-chart-card mb-3">
    <div class="acp-dash-card-head">
      <div<?= acp_tip_attrs('Website Visits and News & Events', 'The top chart shows website visits and the bottom chart shows news and events published, on the same timeline. Look for visits rising just after something is published.', 'bi-megaphone') ?>>
        <div class="acp-form-section-title mb-0">Does Publishing Bring Visitors?</div>
        <p class="small text-muted mb-0">Website visits and news &amp; events published, <?= esc($range) ?></p>
      </div>
    </div>
    <div class="acp-chart-wrap acp-chart-wrap-sm"><canvas id="chartImpactVisits" role="img" aria-label="Website visits over the selected period."></canvas></div>
    <div class="acp-chart-wrap acp-chart-wrap-xs"><canvas id="chartImpactPosts" role="img" aria-label="News and events published over the selected period."></canvas></div>
  </div>

  <div class="acp-card acp-dash-panel mb-3">
    <div class="acp-dash-card-head mb-2">
      <div class="acp-form-section-title mb-0"<?= acp_tip_attrs('Most Opened Pages', 'The pages people opened most, how many different people opened each one, and how long they spent on it on average.', 'bi-trophy') ?>><i class="bi bi-trophy"></i> Most Opened Pages</div>
    </div>
    <?php if (! $data['pages']): ?>
      <div class="acp-empty py-2">No data for this period.</div>
    <?php else: ?>
      <div class="acp-table-wrap">
        <table class="acp-table acp-report-table">
          <thead><tr><th>Page</th><th>Address</th><th>Times Opened</th><th>People</th><th>Average Time Spent</th></tr></thead>
          <tbody>
            <?php foreach ($data['pages'] as $pg): ?>
              <tr>
                <td class="acp-detail-item"><strong><?= esc($notKnown($pg['pageTitle'])) ?></strong></td>
                <td class="acp-detail-item text-muted"><?= esc($pg['pagePath']) ?></td>
                <td><?= number_format((int) $pg['screenPageViews']) ?></td>
                <td><?= number_format((int) $pg['activeUsers']) ?></td>
                <td><?= esc($duration($pg['activeUsers'] > 0 ? $pg['userEngagementDuration'] / $pg['activeUsers'] : 0)) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="row g-3 mb-3">
    <?= $panel('How People Found Us', 'The way people arrived at the website.', 'bi-signpost-split', $rankList($data['channels'], 'sessionDefaultChannelGroup', 'sessions', $named(GA::CHANNELS), 'visits'), '"Google or another search engine" means they searched and clicked our result.') ?>
    <?= $panel('Websites That Sent Visitors', 'The exact websites or apps people came from. "direct" means no other website sent them.', 'bi-link-45deg', $rankList($data['sources'], 'sessionSource', 'sessions', $named(['(direct)' => 'direct']), 'visits')) ?>
    <?= $panel('First Page People Saw', 'The page each visit started on. These are the doors people use to enter the website.', 'bi-door-closed', $rankList($data['landing'], 'landingPage', 'sessions', $notKnown, 'visits')) ?>
    <?= $panel('Countries', 'Where visitors were when they used the website.', 'bi-globe-asia-australia', $rankList($data['countries'], 'country', 'activeUsers', $notKnown, 'people')) ?>
    <?= $panel('Cities', 'The cities visitors were in. Google estimates this, and some visits cannot be placed.', 'bi-geo-alt', $rankList($data['cities'], 'city', 'activeUsers', $notKnown, 'people')) ?>
    <?= $panel('New and Returning Visitors', 'How many people were here for the first time and how many came back again.', 'bi-arrow-repeat', $rankList($data['visitors'], 'newVsReturning', 'activeUsers', $named(GA::VISITOR_TYPES), 'people')) ?>
    <?= $panel('Phone or Computer', 'The kind of device people used.', 'bi-phone', $rankList($data['devices'], 'deviceCategory', 'activeUsers', $named(GA::DEVICES), 'people'), 'If most people use phones, check how pages look on a phone first.') ?>
    <?= $panel('Internet Browsers', 'The app people used to open the website, such as Chrome or Safari.', 'bi-browser-chrome', $rankList($data['browsers'], 'browser', 'activeUsers', $notKnown, 'people')) ?>
    <?= $panel('Device Systems', 'The system running on the phone or computer, such as Android, iPhone (iOS) or Windows.', 'bi-windows', $rankList($data['systems'], 'operatingSystem', 'activeUsers', $notKnown, 'people')) ?>
    <?= $panel('Languages', "The language set on the visitor's phone or computer.", 'bi-translate', $rankList($data['languages'], 'language', 'activeUsers', $notKnown, 'people')) ?>
    <?php
      $actions = array_values(array_filter($data['events'], static fn ($e) => isset(GA::EVENTS[$e['eventName']])));
      echo $panel('What People Did on the Website', 'Things people did while on the website and how many times each one happened.', 'bi-hand-index', $rankList($actions, 'eventName', 'eventCount', $named(GA::EVENTS), 'times'));
      if ($data['ages']) {
          echo $panel('Age Groups', 'Estimated age of visitors. Google only knows this for some people.', 'bi-person-lines-fill', $rankList($data['ages'], 'userAgeBracket', 'activeUsers', $notKnown, 'people'));
      }
      if ($data['genders']) {
          echo $panel('Men and Women', 'Estimated share of visitors. Google only knows this for some people.', 'bi-gender-ambiguous', $rankList($data['genders'], 'userGender', 'activeUsers', $named(GA::GENDERS), 'people'));
      }
    ?>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-lg-5">
      <div class="acp-card acp-chart-card">
        <div class="acp-form-section-title"<?= acp_tip_attrs('Busiest Days', 'Total visits on each day of the week. A good guide for when to publish news.', 'bi-calendar-week') ?>><i class="bi bi-calendar-week"></i> Busiest Days</div>
        <div class="acp-chart-wrap acp-chart-wrap-sm"><canvas id="chartDays" role="img" aria-label="Visits by day of the week."></canvas></div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="acp-card acp-chart-card">
        <div class="acp-form-section-title"<?= acp_tip_attrs('Busiest Hours', 'Total visits at each hour of the day, in the time zone set in Google Analytics.', 'bi-clock') ?>><i class="bi bi-clock"></i> Busiest Hours</div>
        <div class="acp-chart-wrap acp-chart-wrap-sm"><canvas id="chartHours" role="img" aria-label="Visits by hour of the day."></canvas></div>
      </div>
    </div>
  </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($ok):
  $byMonth = $period['months'] > 2;
  $dim     = $byMonth ? 'yearMonth' : 'date';
  $trend   = array_column($data['trend'], null, $dim);
  $points  = [];
  $cursor  = strtotime($byMonth ? substr($period['start'], 0, 7) . '-01' : $period['start']);
  while ($cursor <= strtotime($period['last_day'])) {
      $row      = $trend[date($byMonth ? 'Ym' : 'Ymd', $cursor)] ?? [];
      $points[] = [date($byMonth ? 'M Y' : 'd M', $cursor), (int) ($row['activeUsers'] ?? 0), (int) ($row['sessions'] ?? 0), (int) ($row['screenPageViews'] ?? 0), $published[date($byMonth ? 'Ym' : 'Ymd', $cursor)] ?? 0];
      $cursor   = strtotime($byMonth ? '+1 month' : '+1 day', $cursor);
  }
  $days = array_fill(0, 7, 0);
  foreach ($data['days'] as $d) {
      $days[(int) $d['dayOfWeek']] = (int) $d['sessions'];
  }
  $hours = array_fill(0, 24, 0);
  foreach ($data['hours'] as $h) {
      $hours[(int) $h['hour']] = (int) $h['sessions'];
  }
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
<script>
(function () {
  const colors = ['#1F7FA3', '#BE7A2A', '#6A5FB0'];
  const points = <?= json_encode($points, JSON_UNESCAPED_UNICODE) ?>;
  const days = <?= json_encode($days) ?>;
  const hours = <?= json_encode($hours) ?>;
  const font = { family: 'Quicksand', size: 11 };
  const grid = { color: 'rgba(213,224,232,.7)' };
  Chart.defaults.font.family = 'Quicksand';
  Chart.defaults.color = '#6e8294';

  const line = (label, i) => ({
    label,
    data: points.map((p) => p[i + 1]),
    borderColor: colors[i],
    backgroundColor: colors[i],
    borderWidth: 2,
    tension: .3,
    pointRadius: points.length > 40 ? 0 : 3,
    pointHoverRadius: 5
  });
  new Chart(document.getElementById('chartVisits'), {
    type: 'line',
    data: { labels: points.map((p) => p[0]), datasets: [line('People', 0), line('Visits', 1), line('Pages opened', 2)] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, padding: 14, font } } },
      scales: {
        x: { grid: { display: false }, ticks: { font, maxRotation: 0, autoSkip: true } },
        y: { beginAtZero: true, ticks: { precision: 0, font }, grid }
      }
    }
  });

  // Two charts on one timeline, because visits and published items are very different sizes.
  const timeline = (id, type, label, i, data, showDates) => new Chart(document.getElementById(id), {
    type,
    data: {
      labels: points.map((p) => p[0]),
      datasets: [{
        label,
        data,
        borderColor: colors[i],
        backgroundColor: colors[i],
        borderWidth: 2,
        borderRadius: 3,
        tension: .3,
        pointRadius: 0,
        pointHoverRadius: 5,
        maxBarThickness: 18
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { display: false }, title: { display: true, text: label, align: 'start', font: { ...font, weight: '600' }, padding: { bottom: 4 } } },
      scales: {
        x: { offset: true, grid: { display: false }, ticks: { display: showDates, font, maxRotation: 0, autoSkip: true } },
        y: { beginAtZero: true, afterFit: (axis) => { axis.width = 46; }, ticks: { precision: 0, font }, grid }
      }
    }
  });
  timeline('chartImpactVisits', 'line', 'Website visits', 0, points.map((p) => p[2]), false);
  timeline('chartImpactPosts', 'bar', 'News and events published', 1, points.map((p) => p[4]), true);

  const columns = (id, labels, data) => new Chart(document.getElementById(id), {
    type: 'bar',
    data: { labels, datasets: [{ label: 'Visits', data, backgroundColor: colors[0], borderRadius: 4, maxBarThickness: 28 }] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font, maxRotation: 0, autoSkip: true } },
        y: { beginAtZero: true, ticks: { precision: 0, font }, grid }
      }
    }
  });
  columns('chartDays', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], days);
  columns('chartHours', hours.map((_, h) => (h % 12 || 12) + (h < 12 ? ' am' : ' pm')), hours);
})();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
