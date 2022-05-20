<?php

const ERROR_REQUIRE = 'veuillez renseigner ce champ';
const ERROR_TOO_SHORT = 'veuillez entrer au moins 5 characteres';

$filename = __DIR__ . "/data/todos.json";
$error = '';
$todo = '';
$todos = [];
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $todo = $_POST['todo'] ?? '';
    if (!$todo) {
        $error = ERROR_REQUIRE;
    } else if (mb_strlen($todo) < 5) {
        $error = ERROR_TOO_SHORT;
    }
    if (!$error) {
        $todos = [
            ...$todos,
            [
                'name' => $todo,
                'done' => false,
                'id' => time(),
            ]
        ];
        file_put_contents($filename, json_encode($todos));
    }
}

print_r("valeur de todo est : " . $todo);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "includes/head.php" ?>
</head>


<body>
    <div class="container">
        <?php require_once "includes/header.php" ?>

        <div class="content">
            <div class="todo-container">
                <h1>Ma todo list</h1>
                <form action="/" method="POST" class="todo-form">
                    <input value="<?= $todo ?>" type="text" name="todo" id="todo">
                    <button class="btn btn-primary">ajouter</button>
                </form>
                <?php if ($error) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">

                    <?php foreach ($todos as $todo) : ?>

                        <li class="todo-item">
                            <span class="todo-name"><?= $todo['name'] ?></span>
                            <a href="./edit-todo.php? id=<?= $todo['id'] ?>">
                                <button class="btn btn-primary btn-small">valider</button>
                            </a>
                            <a href="./delete-todo.php">
                                <button class="btn btn-danger btn-small">supprimer</button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>
    <?php require_once "includes/footer.php" ?>

    </div>
</body>

</html>