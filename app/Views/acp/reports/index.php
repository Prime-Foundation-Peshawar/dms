<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$r       = $report;
$period  = $r['period'];
$totals  = $r['totals'];
$an      = $r['analytics'];
$missing = $r['missing'];
$label   = $period['label'];
$range   = $period['range_label'];
$google  = $an['source'] === 'google';
$typeLabels = ['month' => 'Monthly', 'quarter' => 'Quarterly', 'year' => 'Annual', 'range' => 'Custom'];
$exportUrl  = site_url('acp/reports/export') . '?period=' . rawurlencode($period['type']) . '&ref=' . rawurlencode($period['ref']);
$analyticsUrl = site_url('acp/website-analytics') . '?period=' . rawurlencode($period['type']) . '&ref=' . rawurlencode($period['ref']);
if ($period['type'] === 'range') {
    [$rangeFrom, $rangeTo] = explode('_', $period['ref'], 2);
    $analyticsUrl = site_url('acp/website-analytics') . '?period=range&from=' . rawurlencode($rangeFrom) . '&to=' . rawurlencode($rangeTo);
}
$plural  = static fn (int $n, string $word) => $n . ' ' . $word . ($n === 1 ? '' : 's');
$outOf   = static fn (int $n, int $total) => number_format($n) . '<small>/' . number_format($total) . '</small>';
?>
<div class="acp-page-head acp-dash-head acp-report-head">
  <div>
    <h1 class="acp-dash-title"><?= esc($typeLabels[$period['type']]) ?> Report</h1>
    <p class="acp-report-range-title"><i class="bi bi-calendar3"></i> Data from <?= esc($range) ?></p>
    <span class="acp-print-only small text-muted">Generated <?= esc(date('d M Y, H:i')) ?></span>
  </div>
  <div class="d-flex gap-2 flex-wrap acp-no-print">
    <button type="button" class="btn-acp btn-acp-outline" onclick="window.print()"><i class="bi bi-printer"></i> Print / PDF</button>
    <a class="btn-acp btn-acp-primary" href="<?= esc($exportUrl) ?>"><i class="bi bi-download"></i> Download Excel (CSV)</a>
  </div>
</div>

<?= view('acp/reports/_filter', ['period' => $period, 'options' => $r['options'], 'action' => 'acp/reports']) ?>

<?php if (! $r['hrms_ok']): ?>
  <div class="acp-flash acp-flash-error acp-no-print">The staff list (HRMS) could not be reached, so faculty totals below only include people who already have a profile on the website.</div>
<?php endif; ?>

<div class="row g-3 mb-3 acp-kpi-row">
  <div class="col-6 col-md-4 col-xl-4">
    <?= view('acp/reports/_kpi', ['tone' => 'gold', 'label' => 'Pending Activity Submissions', 'icon' => 'bi-hourglass-split', 'href' => '#missing',
        'num' => $outOf(count($missing['no_activity']), (int) $totals['departments']), 'sub' => 'departments sent no activity',
        'tip' => ['Pending Activity Submissions', "Departments that have not sent a single activity from {$range}. Change the period above to check any other month.", "If October is selected, this shows who sent nothing for October."]]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-4">
    <?= view('acp/reports/_kpi', ['tone' => 'teal', 'label' => 'Total Activities', 'icon' => 'bi-clipboard-check', 'href' => '#details',
        'num' => number_format((int) $totals['activities']), 'sub' => $totals['awaiting_review'] ? $totals['awaiting_review'] . ' waiting for review' : 'from all departments',
        'tip' => ['Total Activities', "All activities received from departments from {$range}, counting what is published plus what is still waiting for review. Rejected forms are not counted.", 'Workshops, seminars, conferences and similar events are all activities.']]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-4">
    <?= view('acp/reports/_kpi', ['tone' => 'teal', 'label' => 'Upcoming Events', 'icon' => 'bi-calendar-event', 'href' => '#upcoming',
        'num' => (string) count($r['upcoming']), 'sub' => $totals['news'] . ' news & events published in this period',
        'tip' => ['Upcoming Events', 'Events that have not started yet. The small line shows how many news and event items were published in the selected period.']]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <?= view('acp/reports/_kpi', ['tone' => 'navy', 'label' => 'Total Faculty', 'icon' => 'bi-people', 'href' => '#details',
        'num' => number_format((int) $totals['faculty']), 'sub' => 'as of today, all departments',
        'tip' => ['Total Faculty', 'Everyone working in a department today, taken from the HR staff list and the website. Each person is counted once. This does not change with the period above.']]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <?= view('acp/reports/_kpi', ['tone' => 'teal', 'label' => 'Faculty Profiles Done', 'icon' => 'bi-person-check', 'href' => '#details',
        'num' => $outOf((int) $totals['available'], (int) $totals['faculty']), 'sub' => number_format((int) $totals['faculty_updates']) . ' received in this period',
        'tip' => ['Faculty Profiles Done', 'Faculty members who have provided their details, meaning their profile shows experience, publications or skills.', 'A name with an empty profile is counted as pending.']]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <?= view('acp/reports/_kpi', ['tone' => 'gold', 'label' => 'Faculty Profiles Pending', 'icon' => 'bi-person-x', 'href' => '#details',
        'num' => number_format((int) $totals['faculty_missing']), 'sub' => 'faculty have not provided details',
        'tip' => ['Faculty Profiles Pending', 'Faculty members with no details on the website yet. Open any department in the list below to see their names.', 'If Anatomy has 12 faculty and 5 have provided details, 7 are pending.']]) ?>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <?php if ($google): ?>
      <?= view('acp/reports/_kpi', ['tone' => 'gold', 'label' => 'Website Analytics', 'icon' => 'bi-globe2', 'href' => $analyticsUrl,
          'num' => number_format((int) $an['totals']['activeUsers']), 'sub' => 'people visited · ' . number_format((int) $an['totals']['sessions']) . ' visits',
          'tip' => ['Website Analytics', "Different people who came to the public website from {$range}, measured by Google Analytics.", 'Click to open the full Website Analytics page.']]) ?>
    <?php else: ?>
      <?= view('acp/reports/_kpi', ['tone' => 'gold', 'label' => 'Website Analytics', 'icon' => 'bi-globe2', 'href' => $analyticsUrl,
          'num' => number_format((int) $an['views']), 'sub' => 'pages opened, counted by this site',
          'tip' => ['Website Analytics', 'How many times pages on the public website were opened, counted by this site because Google Analytics is not connected yet.', 'Click to open the Website Analytics page.']]) ?>
    <?php endif; ?>
  </div>
</div>

<?php
$doneTotal = (int) $totals['available'];
$facTotal  = (int) $totals['faculty'];
$donePct   = $facTotal ? (int) round($doneTotal / $facTotal * 100) : 0;
?>
<div class="row g-3 mb-3">
  <div class="col-lg-4">
    <div class="acp-card acp-chart-card h-100">
      <div class="acp-form-section-title"<?= acp_tip_attrs('Overall Progress', 'How much of the faculty profile work is finished across all departments, as of today.', 'bi-pie-chart', "{$doneTotal} of {$facTotal} faculty have provided their details.") ?>>Overall Progress</div>
      <div class="acp-ring">
        <canvas id="chartProgress" role="img" aria-label="<?= $doneTotal ?> of <?= $facTotal ?> faculty profiles done."></canvas>
        <div class="acp-ring-mid"><strong><?= $donePct ?>%</strong><span><?= number_format($doneTotal) ?> of <?= number_format($facTotal) ?></span></div>
      </div>
      <ul class="acp-ring-key">
        <li><i style="background:#1F7FA3"></i> Profiles done <strong><?= number_format($doneTotal) ?></strong></li>
        <li><i style="background:#BE7A2A"></i> Profiles pending <strong><?= number_format($facTotal - $doneTotal) ?></strong></li>
      </ul>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="acp-card acp-chart-card h-100">
      <div class="acp-dash-card-head">
        <div<?= acp_tip_attrs('Faculty Profiles Over Time', 'The running total of profiles done. When the line gets closer to the total faculty line, the backlog is shrinking. Each profile is dated by its last save, and this does not change with the period above.', 'bi-graph-up-arrow') ?>>
          <div class="acp-form-section-title mb-0">Faculty Profiles Over Time</div>
          <p class="small text-muted mb-0">Profiles done, compared with total faculty</p>
        </div>
      </div>
      <div class="acp-chart-wrap"><canvas id="chartGrowth" role="img" aria-label="Running total of faculty profiles done over time, compared with total faculty."></canvas></div>
    </div>
  </div>
</div>

<div class="acp-card acp-dash-panel mb-3" id="details">
  <div class="acp-dash-card-head">
    <div<?= acp_tip_attrs('Department by Department', 'Each department with its faculty profiles and activities. Click a department to see who is done, who is pending and which activities were received.', 'bi-list-check') ?>>
      <div class="acp-form-section-title mb-0">Department by Department</div>
      <p class="small text-muted mb-0">Faculty profiles done and pending, and activities received, A to Z</p>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center acp-no-print">
      <button type="button" class="btn-acp btn-acp-sm btn-acp-outline" id="detailToggleAll">Open all</button>
    </div>
  </div>
  <div class="acp-detail-list">
    <?php foreach ($r['details'] as $d): ?>
      <details class="acp-detail">
        <summary>
          <span class="acp-detail-name">
            <strong><?= esc($d['name']) ?></strong>
            <span class="text-muted small"><?= esc($d['unit']) ?></span>
          </span>
          <span class="acp-detail-counts">
            <span class="acp-count <?= $d['missing'] ? 'miss' : 'has' ?>"<?= acp_tip_attrs('Faculty Profiles', "{$d['available']} of {$d['faculty']} faculty in {$d['name']} have provided their details, so {$d['missing']} are pending.", 'bi-person-vcard') ?>><?= $d['available'] ?> of <?= $d['faculty'] ?> profiles done<?= $d['missing'] ? ' · ' . $d['missing'] . ' pending' : '' ?></span>
            <span class="acp-count <?= $d['months_missing'] ? 'miss' : 'has' ?>"<?= acp_tip_attrs('Activities', ($d['activities'] === 1 ? '1 activity' : $d['activities'] . ' activities') . ' received. Sent something in ' . ($d['months_total'] - $d['months_missing']) . ' of the ' . $plural($d['months_total'], 'month') . ' selected, so ' . $plural($d['months_missing'], 'month') . ' missing.', 'bi-clipboard-check') ?>><?= $d['activities'] === 1 ? '1 activity' : $d['activities'] . ' activities' ?><?= $d['months_missing'] ? ' · ' . $plural($d['months_missing'], 'month') . ' missing' : '' ?></span>
          </span>
          <span class="acp-detail-meta text-muted"><?= $d['last'] !== '' ? 'Last received ' . esc(date('d M Y', strtotime($d['last']))) : 'Nothing received' ?></span>
        </summary>
        <div class="acp-detail-body">
          <div class="acp-detail-block is-done">
            <strong><i class="bi bi-person-check"></i> Profiles done (<?= count($d['done_names']) ?>)</strong>
            <p class="mb-0"><?= $d['done_names'] ? esc(implode(', ', $d['done_names'])) : 'None yet.' ?></p>
          </div>
          <div class="acp-detail-block is-pending">
            <strong><i class="bi bi-person-x"></i> Profiles pending (<?= count($d['missing_names']) ?>)</strong>
            <p class="mb-0"><?= $d['missing_names'] ? esc(implode(', ', $d['missing_names'])) : 'None.' ?></p>
          </div>
          <div class="acp-detail-block">
            <strong><i class="bi bi-clipboard-check"></i> Activities (<?= count($d['activity_items']) ?>)</strong>
            <?php if (! $d['activity_items']): ?>
              <p class="mb-0">None received in <?= esc($label) ?>.</p>
            <?php else: ?>
              <ul class="mb-0">
                <?php foreach ($d['activity_items'] as $it): ?>
                  <li><?= esc($it['item']) ?> <span class="text-muted">· <?= esc(date('d M Y', strtotime($it['date']))) ?><?= $it['status'] === 'pending' ? ' · waiting for review' : '' ?></span></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </details>
    <?php endforeach; ?>
  </div>
</div>

<div class="acp-card acp-dash-panel mb-3" id="missing">
  <div class="acp-dash-card-head">
    <div<?= acp_tip_attrs('Who to Follow Up With', 'The departments to contact for this period. The number beside a department is how many profiles it still has pending.', 'bi-megaphone') ?>>
      <div class="acp-form-section-title mb-0">Who to Follow Up With</div>
      <p class="small text-muted mb-0">Activities and faculty profiles still needed, <?= esc($range) ?></p>
    </div>
  </div>
  <div class="acp-dash-attn">
    <div class="acp-dash-attn-col">
      <div class="acp-dash-attn-label"><i class="bi bi-hourglass-split"></i> Pending Activity Submissions (<?= count($missing['no_activity']) ?>)</div>
      <?php if (! $missing['no_activity']): ?>
        <div class="acp-empty py-3">Every department sent at least one activity.</div>
      <?php else: ?>
        <div class="acp-chip-list">
          <?php foreach ($missing['no_activity'] as $d): $href = 'acp/departments/edit/' . $d['id']; ?>
            <?= acp_link_open($href, 'acp-chip') ?><?= esc($d['name']) ?><?= acp_link_close($href) ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="acp-dash-attn-col">
      <div class="acp-dash-attn-label"><i class="bi bi-person-x"></i> Faculty Profiles Pending (<?= count($missing['profiles']) ?> departments)</div>
      <?php if (! $missing['profiles']): ?>
        <div class="acp-empty py-3">All faculty profiles are done.</div>
      <?php else: ?>
        <div class="acp-chip-list">
          <?php foreach ($missing['profiles'] as $d): $href = 'acp/departments/edit/' . $d['id']; ?>
            <?= acp_link_open($href, 'acp-chip') ?><?= esc($d['name']) ?> (<?= $d['faculty'] - $d['available'] ?>)<?= acp_link_close($href) ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <?php $tierTips = [
      'top'     => ['Top Contributors', 'Departments that sent well above the average number of activities and faculty profiles in this period.', "The average is {$r['average']} items per department."],
      'average' => ['Average Contributors', 'Departments that sent around the average number of activities and faculty profiles in this period.', "The average is {$r['average']} items per department."],
      'low'     => ['Low Contributors', 'Departments that sent well below the average, or nothing at all, in this period. Lowest first.', "The average is {$r['average']} items per department."],
  ]; ?>
  <?php foreach (['top' => 'bi-trophy', 'average' => 'bi-dash-circle', 'low' => 'bi-arrow-down-circle'] as $tier => $icon):
    $list = $r['tiers'][$tier];
    if ($tier === 'low') {
        $list = array_reverse($list);
    }
  ?>
    <div class="col-lg-4">
      <div class="acp-card acp-dash-panel h-100 acp-tier acp-tier-<?= $tier ?>">
        <div class="acp-dash-card-head"<?= acp_tip_attrs($tierTips[$tier][0], $tierTips[$tier][1], $icon, $tierTips[$tier][2]) ?>>
          <div class="acp-form-section-title mb-0"><i class="bi <?= $icon ?>"></i> <?= esc($tierTips[$tier][0]) ?></div>
          <span class="acp-badge acp-badge-info"><?= count($list) ?></span>
        </div>
        <?php if (! $list): ?>
          <div class="acp-empty py-3">None in this period.</div>
        <?php else: ?>
          <ol class="acp-tier-list mb-0">
            <?php foreach (array_slice($list, 0, 8) as $d): $acts = $d['activities'] + $d['submissions']; ?>
              <li>
                <?= acp_link_open('acp/departments/edit/' . $d['id']) ?><?= esc($d['name']) ?><?= acp_link_close('acp/departments/edit/' . $d['id']) ?>
                <strong><?= (int) $d['contributions'] ?></strong>
                <span class="d-block text-muted small"><?= $acts === 1 ? '1 activity' : $acts . ' activities' ?> · <?= $plural((int) $d['faculty_updates'], 'profile') ?></span>
              </li>
            <?php endforeach; ?>
          </ol>
          <?php if (count($list) > 8): ?>
            <p class="small text-muted mt-2 mb-0">and <?= count($list) - 8 ?> more</p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php
$byPending = array_values(array_filter($r['departments'], static fn ($d) => $d['faculty'] > 0));
usort($byPending, static fn ($a, $b) => [$b['faculty'] - $b['available'], $a['name']] <=> [$a['faculty'] - $a['available'], $b['name']]);
?>
<div class="row g-3 mb-3">
  <div class="col-lg-6">
    <div class="acp-card acp-chart-card h-100">
      <div class="acp-dash-card-head">
        <div<?= acp_tip_attrs('Profile Completion by Department', 'Each bar is one department, split into profiles done and pending. Departments with the most pending profiles are at the top. Hover over a bar for the exact numbers.', 'bi-bar-chart-steps') ?>>
          <div class="acp-form-section-title mb-0">Profile Completion by Department</div>
          <p class="small text-muted mb-0">Most pending at the top, as of today</p>
        </div>
      </div>
      <div class="acp-chart-wrap" style="height: <?= max(260, count($byPending) * 24) ?>px">
        <canvas id="chartCompletion" role="img" aria-label="Faculty profiles done and pending in each department. The same numbers appear in the Department by Department list."></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="acp-card acp-chart-card h-100">
      <div class="acp-dash-card-head">
        <div<?= acp_tip_attrs('Contributions by Department', 'Each bar adds up the activities and faculty profiles a department sent in this period. Hover over a bar for the exact numbers.', 'bi-bar-chart') ?>>
          <div class="acp-form-section-title mb-0">Contributions by Department</div>
          <p class="small text-muted mb-0">Activities and faculty profiles received, <?= esc($range) ?></p>
        </div>
      </div>
      <div class="acp-chart-wrap" style="height: <?= max(260, count($byPending) * 24) ?>px">
        <canvas id="chartContributions" role="img" aria-label="Activities and faculty profiles received by each department. The same numbers appear in the Department by Department list."></canvas>
      </div>
    </div>
  </div>
</div>

<div class="acp-card acp-dash-panel mb-3">
  <div class="acp-dash-card-head">
    <div<?= acp_tip_attrs('Activity Calendar', 'Each row is a department and each column a month. Each colour is a count, shown in the key above the calendar. Grey squares are the months a department sent nothing. This always shows the last 12 months.', 'bi-calendar3', 'Read across a row to spot the gaps for that department.') ?>>
      <div class="acp-form-section-title mb-0">Activity Calendar</div>
      <p class="small text-muted mb-0">Activities received each month, last 12 months</p>
    </div>
    <div class="acp-heat-key">
      <?php foreach (['None', '1', '2', '3', '4+'] as $lvl => $text): ?><span class="lvl-<?= $lvl ?>"><?= $text ?></span><?php endforeach; ?>
    </div>
  </div>
  <div class="acp-table-wrap">
    <table class="acp-heat">
      <thead>
        <tr>
          <th scope="col">Department</th>
          <?php foreach ($r['trend']['months'] as $m): ?>
            <th scope="col"><?= esc(date('M', strtotime($m . '-01'))) ?><small><?= esc(date('Y', strtotime($m . '-01'))) ?></small></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($r['details'] as $d): ?>
          <tr>
            <th scope="row"><?= esc($d['name']) ?></th>
            <?php foreach ($r['trend']['months'] as $m):
              $n = (int) ($r['trend']['calendar'][$d['id']][$m] ?? 0);
            ?>
              <td class="lvl-<?= min($n, 4) ?>" title="<?= esc($d['name'] . ', ' . date('F Y', strtotime($m . '-01')) . ': ' . ($n === 1 ? '1 activity' : $n . ' activities'), 'attr') ?>"><?= $n ?: '' ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="acp-card acp-dash-panel mb-3" id="website">
  <div class="acp-dash-card-head">
    <div<?= acp_tip_attrs('Website Analytics', $google ? 'A short summary from Google Analytics. Open the full page for where visitors came from, what they read and which devices they used.' : 'Google Analytics is not connected yet, so this shows the pages opened as counted by this site.', 'bi-globe2') ?>>
      <div class="acp-form-section-title mb-0">Website Analytics</div>
      <p class="small text-muted mb-0"><?= $google ? 'From Google Analytics, ' . esc($range) : 'Google Analytics is not connected yet. Pages opened are counted by this site.' ?></p>
    </div>
    <a class="btn-acp btn-acp-sm btn-acp-outline acp-no-print" href="<?= esc($analyticsUrl) ?>">Open Website Analytics <i class="bi bi-arrow-right-short"></i></a>
  </div>
  <div class="acp-mini-stats">
    <?php if ($google): ?>
      <div class="acp-mini-stat" tabindex="0"<?= acp_tip_attrs('People Who Visited', 'Different people who came to the website. Someone who came three times counts once.', 'bi-people') ?>><strong><?= number_format((int) $an['totals']['activeUsers']) ?></strong><span>People who visited</span></div>
      <div class="acp-mini-stat" tabindex="0"<?= acp_tip_attrs('Total Visits', 'Each time someone came to the website. Someone who came three times counts as three visits.', 'bi-door-open') ?>><strong><?= number_format((int) $an['totals']['sessions']) ?></strong><span>Total visits</span></div>
    <?php endif; ?>
    <div class="acp-mini-stat" tabindex="0"<?= acp_tip_attrs('Pages Opened', 'How many pages were opened in total on the public website.', 'bi-file-earmark-text') ?>><strong><?= number_format((int) $an['views']) ?></strong><span>Pages opened</span></div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-6">
    <div class="acp-card acp-chart-card h-100">
      <div class="acp-dash-card-head"<?= acp_tip_attrs('Data Received Each Month', 'How much data arrived in each of the last 12 months. This chart always shows 12 months, whatever period is selected above.', 'bi-graph-up') ?>>
        <div class="acp-form-section-title mb-0">Data Received, Last 12 Months</div>
      </div>
      <div class="acp-chart-wrap acp-chart-wrap-sm">
        <canvas id="chartTrend" role="img" aria-label="Activities and faculty profiles received in each of the last 12 months."></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6" id="upcoming">
    <div class="acp-card acp-dash-panel h-100">
      <div class="acp-dash-card-head">
        <div class="acp-form-section-title mb-0"<?= acp_tip_attrs('Upcoming Events', 'Published items with an event date that has not started yet, the same list the website shows.', 'bi-calendar-event') ?>>Upcoming Events</div>
        <?php if (acp_can_open('acp/news/create')): ?>
          <a class="btn-acp btn-acp-sm btn-acp-outline acp-no-print" href="<?= site_url('acp/news/create') ?>"><i class="bi bi-plus-lg"></i> Event</a>
        <?php endif; ?>
      </div>
      <?php if (! $r['upcoming']): ?>
        <div class="acp-empty py-3">No upcoming events scheduled.</div>
      <?php else: ?>
        <ul class="acp-feed mb-0">
          <?php foreach ($r['upcoming'] as $e): $href = 'acp/news/edit/' . $e['id']; ?>
            <li>
              <?= acp_link_open($href) ?>
                <span class="acp-feed-title"><?= esc($e['title']) ?></span>
                <span class="acp-feed-meta"><?= esc(date('d M Y', strtotime((string) $e['first_day']))) ?><?= $e['location'] ? ' · ' . esc($e['location']) : '' ?></span>
              <?= acp_link_close($href) ?>
              <span class="acp-badge <?= ($e['status'] ?? '') === 'published' ? 'acp-badge-info' : 'acp-badge-muted' ?>">
                <?= $e['in_days'] === 0 ? 'Today' : 'In ' . $plural((int) $e['in_days'], 'day') ?>
              </span>
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
  const details = [...document.querySelectorAll('.acp-detail')];
  const toggleAll = document.getElementById('detailToggleAll');
  toggleAll.addEventListener('click', function () {
    const open = toggleAll.textContent.trim() === 'Open all';
    details.forEach((d) => { d.open = open; });
    toggleAll.textContent = open ? 'Close all' : 'Open all';
  });
  window.addEventListener('beforeprint', function () {
    details.forEach((d) => { d.open = true; });
  });

  const colors = ['#1F7FA3', '#BE7A2A', '#6A5FB0'];
  const depts = <?= json_encode(array_map(static fn ($d) => ['name' => $d['name'], 'activities' => $d['activities'] + $d['submissions'], 'profiles' => $d['faculty_updates']], $r['departments']), JSON_UNESCAPED_UNICODE) ?>;
  const trend = <?= json_encode(array_diff_key($r['trend'], ['calendar' => 1, 'months' => 1]), JSON_UNESCAPED_UNICODE) ?>;
  const growth = <?= json_encode($r['growth'], JSON_UNESCAPED_UNICODE) ?>;
  const completion = <?= json_encode(array_map(static fn ($d) => ['name' => $d['name'], 'done' => $d['available'], 'pending' => $d['faculty'] - $d['available']], $byPending), JSON_UNESCAPED_UNICODE) ?>;
  const font = { family: 'Quicksand', size: 11 };
  const grid = { color: 'rgba(213,224,232,.7)' };
  const legend = { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, padding: 10, font } };
  const series = (label, data, i) => ({ label, data, backgroundColor: colors[i], borderColor: '#fff', borderWidth: 1, borderRadius: 3 });
  Chart.defaults.font.family = 'Quicksand';
  Chart.defaults.color = '#6e8294';

  new Chart(document.getElementById('chartProgress'), {
    type: 'doughnut',
    data: {
      labels: ['Profiles done', 'Profiles pending'],
      datasets: [{ data: [<?= $doneTotal ?>, <?= $facTotal - $doneTotal ?>], backgroundColor: [colors[0], colors[1]], borderColor: '#fff', borderWidth: 2 }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '76%', plugins: { legend: { display: false } } }
  });

  new Chart(document.getElementById('chartGrowth'), {
    type: 'line',
    data: {
      labels: growth.labels,
      datasets: [
        { label: 'Profiles done', data: growth.done, borderColor: colors[0], backgroundColor: colors[0], borderWidth: 2, stepped: true, pointRadius: 0, pointHoverRadius: 5 },
        { label: 'Total faculty', data: growth.labels.map(() => growth.total), borderColor: '#6e8294', backgroundColor: '#6e8294', borderWidth: 2, borderDash: [6, 5], pointRadius: 0, pointHoverRadius: 0 }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend },
      scales: {
        x: { grid: { display: false }, ticks: { font, maxRotation: 0, autoSkip: true, maxTicksLimit: 10 } },
        y: { beginAtZero: true, ticks: { precision: 0, font }, grid }
      }
    }
  });

  new Chart(document.getElementById('chartCompletion'), {
    type: 'bar',
    data: {
      labels: completion.map((d) => d.name),
      datasets: [
        { ...series('Profiles done', completion.map((d) => d.done), 0), maxBarThickness: 14 },
        { ...series('Profiles pending', completion.map((d) => d.pending), 1), maxBarThickness: 14 }
      ]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'y', intersect: false },
      plugins: { legend },
      scales: {
        x: { stacked: true, beginAtZero: true, ticks: { precision: 0, font }, grid },
        y: { stacked: true, grid: { display: false }, ticks: { font: { ...font, size: 10 }, autoSkip: false } }
      }
    }
  });

  new Chart(document.getElementById('chartContributions'), {
    type: 'bar',
    data: {
      labels: depts.map((d) => d.name),
      datasets: [
        { ...series('Activities', depts.map((d) => d.activities), 0), maxBarThickness: 14 },
        { ...series('Faculty profiles', depts.map((d) => d.profiles), 1), maxBarThickness: 14 }
      ]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'y', intersect: false },
      plugins: { legend },
      scales: {
        x: { stacked: true, beginAtZero: true, ticks: { precision: 0, font }, grid },
        y: { stacked: true, grid: { display: false }, ticks: { font: { ...font, size: 10 }, autoSkip: false } }
      }
    }
  });

  new Chart(document.getElementById('chartTrend'), {
    type: 'bar',
    data: {
      labels: trend.labels,
      datasets: [
        series('Activities', trend.activities, 0),
        series('Faculty profiles', trend.faculty, 1)
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend },
      scales: {
        x: { stacked: true, grid: { display: false }, ticks: { font, maxRotation: 0, autoSkip: true } },
        y: { stacked: true, beginAtZero: true, ticks: { precision: 0, font }, grid }
      }
    }
  });
})();
</script>
<?= $this->endSection() ?>
