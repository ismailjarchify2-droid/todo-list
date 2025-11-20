<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Todo App PHP</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        h1 { text-align: center; }
        .todo { background: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px; display: flex; justify-content: space-between; }
        .done { text-decoration: line-through; color: green; }
        button { margin-left: 5px; }
        #box { display: flex; margin-bottom: 20px; }
        #box input { flex: 1; padding: 10px; }
    </style>
</head>
<body>

<h1>Todo List (PHP + JS)</h1>

<div id="box">
    <input id="newTodo" placeholder="Nouvelle tâche...">
    <button onclick="addTodo()">Ajouter</button>
</div>

<div id="list"></div>

<script>
const API = "api.php";

function loadTodos() {
    fetch(API + "?action=list")
    .then(res => res.json())
    .then(data => {
        let html = "";
        data.forEach(t => {
            html += `
            <div class="todo">
                <span class="${t.done == 1 ? 'done' : ''}">${t.title}</span>
                <div>
                    <button onclick="toggle(${t.id})">✓</button>
                    <button onclick="del(${t.id})">X</button>
                </div>
            </div>`;
        });
        document.getElementById("list").innerHTML = html;
    });
}

function addTodo() {
    let title = document.getElementById("newTodo").value.trim();
    if (title === "") return;

    let form = new FormData();
    form.append("action", "add");
    form.append("title", title);

    fetch(API, { method: "POST", body: form })
    .then(() => {
        document.getElementById("newTodo").value = "";
        loadTodos();
    });
}

function del(id) {
    let form = new FormData();
    form.append("action", "delete");
    form.append("id", id);

    fetch(API, { method: "POST", body: form })
    .then(() => loadTodos());
}

function toggle(id) {
    let form = new FormData();
    form.append("action", "toggle");
    form.append("id", id);

    fetch(API, { method: "POST", body: form })
    .then(() => loadTodos());
}

loadTodos();
</script>

</body>
</html>
