<section id="adminNav" class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>

            <?php
            $stmt = $db->prepare("SELECT * FROM categories");
            $stmt->execute();
            $cats =$stmt->fetchAll();
            foreach($cats as $cat):
                $name = $cat['name'];
                $id= $cat['id']; 
            ?>

            <li class="nav-item">
                <a class="nav-link" href="cats.php?id=<?= $id ?>&name=<?= $name ?>"><?= $name ?></a>
            </li>
            <?php endforeach ; ?>
        </ul>
</section>