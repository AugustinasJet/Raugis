<?php $db = new \Core\FileDB(DB_FILE); ?>
<?php $repository = new Core\User\Repository($db, TABLE_USERS); ?>
<?php $session = new \Core\User\Session($repository); ?>

<ul class="nav justify-content-center">
    <?php if (isset($session) && $session->isLoggedIn()): ?>
        <li class="nav-item">
            <a class="nav-link active" href="./index.php">Rap Šmap</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./logout.php">Logout</a>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link active" href="./index.php">Rap Šmap</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="./register.php">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./login.php">Login</a>
        </li>
    <?php endif; ?>
</ul>
