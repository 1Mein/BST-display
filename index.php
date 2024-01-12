<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BST</title>
</head>
<body>
    <form action="operations.php" method="post" id="formis">
        <input type="number" name="val" required><br>
    
        <input type="radio" id="option1" name="option" value="1">
        <label for="option1">Add number</label><br>
        
        <input type="radio" id="option2" name="option" value="2">
        <label for="option2">Delete Number</label><br>

        <button type="submit">Go</button>
    </form>

    <pre class="output" style="margin: 40px; font-family:courier;">
        
    </pre>
</body>
<script>
    function show(data){
        console.log(data);
        const container = document.querySelector(".output");
        container.innerHTML = '';
        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                let item = data[key];
                item = item.replaceAll('.', '┐');
                item = item.replaceAll('|', '│');
                item = item.replaceAll('+', '┤');
                item = item.replaceAll('*', '┌');
                item = item.replaceAll('-', '┘');
                item = item.replaceAll('?', '└');

                const div = document.createElement("div");
                div.textContent = item;
                container.appendChild(div);
            }
        }
    }
    
    fetch('operations.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        show(data);
    })
    .catch(error => console.error('Ошибка:', error));

    document.getElementById("formis").addEventListener("submit", function(event){
        event.preventDefault();

        const formData = new FormData(this);
        fetch('operations.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            show(data);
        })
        .catch(error => console.error('Ошибка:', error));
    });
</script>
</html>

<!-- ┌┐│┤└┘ -->