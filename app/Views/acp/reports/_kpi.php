<?php
/**
 * Count card with a hover explanation.
 *
 * @var string      $tone  teal|navy|gold
 * @var string      $label
 * @var string      $icon
 * @var string      $num   ready-to-print number (escaped by the caller)
 * @var string      $sub
 * @var list<string> $tip  [title, text, example]
 * @var string|null $href  anchor to jump to
 */
$tag   = empty($href) ? 'div' : 'a';
$extra = empty($href) ? ' tabindex="0"' : ' href="' . esc($href, 'attr') . '"';
?>
<<?= $tag ?> class="acp-kpi tone-<?= esc($tone) ?>"<?= $extra ?><?= acp_tip_attrs($tip[0], $tip[1], $icon, $tip[2] ?? '') ?>>
  <div class="acp-kpi-top"><span class="lbl"><?= esc($label) ?></span><i class="bi <?= esc($icon) ?>"></i></div>
  <div class="num"><?= $num ?></div>
  <div class="sub"><?= esc($sub) ?></div>
</<?= $tag ?>>
