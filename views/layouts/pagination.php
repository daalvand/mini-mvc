<nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="/?page={{ $pagination['page'] <=1 ? 1 : $pagination['page'] - 1  }}">Previous</a></li>
        <li class="page-item"><a class="page-link" href="/?page={{ $pagination['page'] >= $pagination['last_page'] ? $pagination['last_page'] : $pagination['page'] + 1  }}">Next</a></li>
    </ul>
</nav>
