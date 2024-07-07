<?
$className = $args['className'] ?? '';
$style = $args['style'] ?? '';
/**
 *  md
 */
$size = $args['size'] ?? 'md';
$color = $args['color'] ?? 'primary';
$icon = $args['icon'] ?? 'circle';
$rounded = $args['rounded'] ?? false;
/**
 * contained | contained-light | contained-dark | outlined | outlined-bold
 */
$variant = $args['variant'] ?? 'contained';
$color = $args['color'] ?? 'primary';

$href = $args['href'] ?? null;

$tag = $href ? 'a' : 'button';

$attrsStr = getAttributesString(array_intersect_key($args, array_flip(['href', 'target', 'rel', 'id', 'title', 'data', 'aria'])));

?>
<<?= $tag ?> class=" <?= classNames(
                $className,
                'afm-icon-button',
                $size ? 'afm-icon-button--size_' . $size : '',
                'afm-icon-button--variant_' . $variant,
                'afm-icon-button--color_' . $color,
                [
                  'afm-icon-button--rounded' => $rounded
                ]

              ) ?>" <?= $attrsStr ?>>
  <span class="material-symbols-outlined">
    <?= $icon ?>
  </span>
</<?= $tag ?>>