<?php
/**
 * Archive Creation Fallback Template
 * archive-{type}.php が無い creation_type 用の保険
 */

$type = $args['type'] ?? '';
get_template_part('template-parts/creation/archive/list', '', ['type' => $type]);
?>
