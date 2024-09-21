<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Lottery App'; ?></title>
</head>
<body>
    <header>
        <h1><?php echo $title ?? 'Lottery App'; ?></h1>
    </header>
    <main>
        <?php echo $content ?? ''; ?>
    </main>
    <footer>
        <p>&copy; 2024 Lottery App</p>
    </footer>
</body>
</html>
