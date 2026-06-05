<nav class="custom-pagination">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
            <a class="page-link page-link-prev" href="#" data-page="<?= $currentPage - 1 ?>">
                <i class="ri-arrow-left-s-line"></i>
            </a>
        </li>
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <?php if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)): ?>
            <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                <a class="page-link page-number" href="#" data-page="<?= $i ?>">
                    <span><?= $i ?></span>
                </a>
            </li>
            <?php elseif($i == $currentPage - 3 || $i == $currentPage + 3): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
        <?php endfor; ?>
        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link page-link-next" href="#" data-page="<?= $currentPage + 1 ?>">
                <i class="ri-arrow-right-s-line"></i>
            </a>
        </li>
    </ul>
</nav>