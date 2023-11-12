<section id="adminNav" class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cats.php">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products.php">products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">members</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">items</a>
            </li>
        </ul>
        <div class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Admin
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="users.php?do=edit&id=<?php echo $_SESSION['id'] ?>">Edit
                            porfile</a></li>
                    <li><a class="dropdown-item" href="#">settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </div>
    </div>
</section>