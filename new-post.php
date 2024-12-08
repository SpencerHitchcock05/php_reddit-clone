<?php
// New-post functionality -- Required feature
    include_once("templates/header.php")
?>


<div  class="d-flex justify-content-center align-items-center h-50">
    <form id="new-post-box" action="" class="d-flex flex-column">
        <label>Title: </label>
        <input id="title" type="text">
        <label>Content: </label>
        <textarea id="content"></textarea>
        <input id="submitPost" type="submit" value="Create New Post" class="btn btn-primary my-3">
    </form>
</div>

<script>

    //submit the new post info and display message if error happens
    document.getElementById("submitPost").addEventListener("click", (e) => {
        e.preventDefault();

        const titleElem = document.getElementById("title")
        const contentElem = document.getElementById("content")

        const title =titleElem.value;
        const content =contentElem.value;

        fetch("api/posts.php", {
            headers: {"Content-Type": "application/json"},
            method: "POST",
            body: JSON.stringify({title: title, content: content})
        })
        .then(resp => {
            if (!resp.ok) {
                throw new Error();
            }
            return resp.text();
        })
        .then(data => {

            console.log(data)
            const success = document.createElement("p")
            success.innerText = "Post successful"

            document.querySelector("#new-post-box").appendChild(success)
        })
        .catch(err => {
            console.log(err)
            const error =document.createElement("p")
            error.classList.add("text-danger")
            error.innerText = "unauthorized action"

            document.querySelector("#new-post-box").appendChild(error)
        })

        titleElem.value = ""
        contentElem.value = ""

    })
</script>

<?php
    include_once("templates/footer.php")
?>