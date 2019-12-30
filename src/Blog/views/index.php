<?= $renderer->render('header.php'); ?>
<h1>Bienvenue sur le blog</h1>
<ul>
    <li>
        <a href="<?= $router->generateUri('blog.show', ['slug' => 'mon-slug']) ?>">Article 1</a>
    </li>
    <li>article 1</li>
    <li>article 1</li>
    <li>article 1</li>
    <li>article 1</li>
</ul>
<?= $renderer->render('footer.php'); ?>
