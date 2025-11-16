<?php
/**
 * Template for displaying categories - MAĞAZA STILI
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="apc-categories-wrapper">
    <ul class="apc-categories-list">
        <?php foreach ($categories as $category) : ?>
            <?php if (!empty($category) && !is_wp_error($category)) : ?>
                <li class="apc-category-item">
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="apc-category-link">
                        <?php echo esc_html($category->name); ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
