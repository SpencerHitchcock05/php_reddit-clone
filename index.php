<?php
// Index functionality
    include_once("./templates/header.php");
?>


<main>
    <div id="post-container" class="d-flex justify-content-center">
        <div id="posts" class="bg-warning">

        </div>
    </div>
</main>


<script>
    fetch("api/posts.php")
    .then(resp => resp.json())
    .then(data => {

        console.log(data)

        const posts = document.getElementById("posts");

        for (let i = 0; i < data.length; i++) {
            const post = document.createElement("div");
            post.classList.add("m-3");

            //const user_name = document.createElement("p");
            //user_name.innerText = data[i].

            const title = document.createElement("h3");
            title.innerText = data[i].title;
            post.appendChild(title);

            const content = document.createElement("p");
            content.innerText = data[i].content;
            post.appendChild(content);

            posts.appendChild(post);
        }
    })
    .catch(err => {console.log(err)})
</script>