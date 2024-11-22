<?php
// Index functionality
    include_once("./templates/header.php");
?>


<main>

    <?php if (!isset($_SESSION["username"])): ?>  
        <div id="login" class="d-flex justify-content-center align-items-center">
            <form id="login-form" action="includes/login.php" class="d-flex flex-column" method="POST">
                <label id="username-input">Username:</label>
                <input id="username-input" name="username" type="text">
                <label for="password-input">Password:</label>
                <input id="password-input" name="password" type="password">
                <input name="submit" type="submit" class="btn btn-primary my-3">
                <?php if (isset($_GET["err"])) { ?><p class="text-danger">Incorrect username or password</p><?php } ?>
            </form>
        </div>
    <?php else: ?>

    <div id="post-container" class="d-flex justify-content-center">
        <div id="posts" class="d-flex justify-content-center flex-column align-items-center">
            <div id="spinnythingy"></div>
        </div>
    </div>

    <?php endif; ?>
</main>


<script>
    fetch("api/posts.php")
    .then(resp => resp.json())
    .then(data => {

        console.log(data)

        const posts = document.getElementById("posts");

        for (let i = 0; i < data.length; i++) {
            const post = document.createElement("div");
            post.classList.add("post");

            const user_name = document.createElement("p");
            user_name.innerText = data[i].username + " " + data[i].created_at;
            post.appendChild(user_name);
            user_name.classList.add("m-0")

            const title = document.createElement("h3");
            title.innerText = data[i].title;
            post.appendChild(title);

            const content = document.createElement("p");
            content.innerText = data[i].content;
            post.appendChild(content);

            const upvotes = document.createElement("p");
            upvotes.innerHTML = "&uarr; " + Math.round(Math.random() * 999);
            post.appendChild(upvotes);

            posts.appendChild(post);

            const spinnythingy = document.getElementById("spinnythingy");
            spinnythingy.classList.add("d-none");
        }
    })
    .catch(err => {console.log(err)})
/*
    document.getElementById("login-form").addEventListener("submit", (e) => {

        const username = document.getElementById("username-input");
        const password = document.getElementById("password-input");

        if (username.value.match(/\w+/) == null) {
            e.preventDefault()
            return
        }
        
        if (!password.value.match(/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*\W).{8,}$/)) {
            e.preventDefault();
        }

        e.preventDefault()

    }) */
</script>