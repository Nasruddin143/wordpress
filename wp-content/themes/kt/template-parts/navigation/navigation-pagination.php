<?php
/**
 * Custom Pagination Override
 */

$links = paginate_links([
    'type' => 'array',
    'prev_text' => '« Prev',
    'next_text' => 'Next »',
]);

if ($links): ?>
    <nav class="custom-pagination my-2" aria-label="Pagination">
        <ul class="pagination mb-0 justify-content-center">
            <?php foreach ($links as $link): ?>
                <li class="page-item <?php echo strpos($link, 'current') !== false ? 'active' : ''; ?>">
                    <?php
                    echo str_replace(
                        ['page-numbers'],
                        ['page-link'],
                        $link
                    );
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>