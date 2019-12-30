<?= $renderer->render('header.php', ['title' => $slug]); ?>
    <h1>Bienvenue sur le post avec le slug : <?= $slug; ?></h1>
<?= $renderer->render('footer.php'); ?>