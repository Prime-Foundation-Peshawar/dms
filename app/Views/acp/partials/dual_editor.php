<?php
/**
 * TinyMCE rich editor (keeps body_html + auto body_text).
 * @var string $nameBase e.g. body → body_html + body_text
 * @var string|null $text
 * @var string|null $html
 * @var string $label
 */
$nameBase = $nameBase ?? 'body';
$textName = $nameBase . '_text';
$htmlName = $nameBase . '_html';
$text = $text ?? '';
$html = $html ?? '';
$label = $label ?? 'Content';
$uid = 'rte_' . preg_replace('/[^a-z0-9_]/i', '', $nameBase) . '_' . substr(md5($nameBase . $label), 0, 6);
$initial = $html !== '' ? $html : ($text !== '' ? '<p>' . nl2br(esc($text)) . '</p>' : '');
?>
<div class="acp-rich-editor" data-rich-editor="<?= esc($uid) ?>">
  <div class="acp-field span-12" style="display:block">
    <label class="form-label"><?= esc($label) ?></label>
    <textarea class="form-control acp-tinymce" id="<?= esc($uid) ?>" name="<?= esc($htmlName) ?>" data-text-name="<?= esc($textName) ?>" rows="12"><?= esc($initial) ?></textarea>
    <textarea class="d-none" name="<?= esc($textName) ?>" aria-hidden="true"><?= esc($text) ?></textarea>
    <div class="form-text">Rich editor saves HTML for the public site. Plain text is generated automatically on save.</div>
  </div>
</div>
