<?php
// Index functionality
    include_once("./templates/header.php");
?>


<main>

    <?php if (!isset($_SESSION["username"])): ?>  

        <!-- both login and create account together only one is shown -->
        <div id="login" class="d-flex justify-content-center align-items-center h-50">
            <form id="login-form" action="includes/login.php" class="d-flex flex-column" method="POST">
                <label id="username-input">Username:</label>
                <input id="username-input" name="username" type="text">
                <label for="password-input">Password:</label>
                <input id="password-input" name="password" type="password">
                <input name="submit" type="submit" class="btn btn-primary my-3">
                <?php if (isset($_GET["err"])) { ?><p class="text-danger">Incorrect username or password</p><?php } ?>
                <button id="new-account-btn" class="text-primary text-decoration-underline">Create a new Account!</button>
            </form>
        </div>

        <div id="new-account" class="d-none justify-content-center align-items-center h-50">
            <form id="new-account-form" action="includes/new_account.php" class="d-flex flex-column" method="POST">
                <label id="username-input">Username:</label>
                <input id="username-input" name="username" type="text">
                <label for="password-input">Password:</label>
                <input id="password-input" name="password" type="password">
                <label for="confirm-input">Confirm Password:</label>
                <input id="confirm-input" name="confirm" type="password">
                <input name="submit" type="submit" class="btn btn-primary my-3">
                <button id="login-btn" class="text-primary text-decoration-underline">Login!</button>
            </form>
        </div>
    <?php else: ?>

        <!-- where all the posts go -->
    <div id="post-container" class="d-flex justify-content-center">
        <div id="posts" class="d-flex justify-content-center flex-column align-items-center">
            <div id="spinnythingy"></div>
        </div>
    </div>

    <?php endif; ?>
</main>


<script>


   

//get all of the posts updated asynchronously
    function getPosts() {

        fetch("api/posts.php")
        .then(resp => resp.json())
        .then(data => {

            console.log(data)

            const posts = document.getElementById("posts");

            posts.innerHTML = "";

            for (let i = 0; i < data.length; i++) {
                const post = document.createElement("div");
                post.classList.add("post");
                post.id = data[i].id

                const editBtn = document.createElement("button")
                editBtn.classList.add("edit-btn")
                editBtn.classList.add("mx-2")
                editBtn.innerText = "Edit"

                editBtn.addEventListener("click", editButton)

                const deleteBtn = document.createElement("button")
                deleteBtn.classList.add("delete-btn")
                deleteBtn.classList.add("mx-2")
                deleteBtn.innerText = "Delete"

                deleteBtn.addEventListener("click", deleteButton)

                const username = <?php if (isset($_SESSION["username"])) {echo "'" . $_SESSION["username"] . "'";}?>

                console.log(username)

                if (data[i].username == username) {
                    post.appendChild(editBtn)
                    post.appendChild(deleteBtn)

                    const editDiv = document.createElement("div")
                    editDiv.classList.add("d-none")
                    editDiv.classList.add("flex-column")
                    editDiv.classList.add("m-3")
                    editDiv.id = `edit-${data[i].id}`
                    

                    const editTitle =document.createElement("input")
                    editTitle.type = "text"
                    editTitle.value = data[i].title
                    const editText = document.createElement("textarea")
                    editText.value = data[i].content

                    const submitEdit =document.createElement("button");
                    submitEdit.innerText = "Submit Edit"
                    submitEdit.classList.add("btn")
                    submitEdit.classList.add("btn-primary")
                    submitEdit.addEventListener("click", () => {submitEditButton(data[i].id, editTitle.value, editText.value)})

                    editDiv.appendChild(editTitle)
                    editDiv.appendChild(editText)
                    editDiv.appendChild(submitEdit)

                    post.appendChild(editDiv)
                }


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

                posts.prepend(post);

                /*
                const spinnythingy = document.getElementById("spinnythingy");
                spinnythingy.classList.add("d-none"); */
            }
        })
        .catch(err => {console.log(err)})

    }

    getPosts()

    let interval = setInterval(() => {getPosts()}, 5000);

    function submitEditButton(id, title, content) {
        console.log(id + title + content)

        fetch("api/posts.php", {
            headers: {"Content-Type": "application/json"},
            method: "PUT",
            body: JSON.stringify({id: id, title: title, content: content})
        })
        .then(resp => {
            if (!resp.ok) {
                alert("edited text cannot be empty")
            }
        })
        .then(data => {console.log(data)})
        .catch(err=>{console.log(err)})


        getPosts()
        interval = setInterval(() => {getPosts()}, 5000)
    }

    //when you hit edit button add or remove the form for editing

    function editButton(e) {
        e.preventDefault()

        clearInterval(interval);


        const editDiv = document.getElementById(`edit-${e.srcElement.parentElement.id}`) 

        if (editDiv.classList.contains("d-none")) {
            editDiv.classList.add("d-flex")
            editDiv.classList.remove("d-none")
        } else {
            editDiv.classList.add("d-none")
            editDiv.classList.remove("d-flex")
        }

        

        console.log("edit")
    }

//asynchrously delete posts

    function deleteButton(e) {
        e.preventDefault()

        const btn = e.srcElement;

        id = btn.parentElement.id
    
        fetch("api/posts.php", {
            headers: {"Content-Type": "application/json"},
            method: "DELETE",
            body: JSON.stringify({id: id})
        }) 
        .then(resp => resp.json())
        .then(data => {
            if (data.success) {
                console.log("deleted")
            } else {
                console.log("uo oh")
            }

            getPosts()
        })
        .catch(err => {console.log(err)})

    }


//code for switching login and createaccount

    document.getElementById("new-account-btn").addEventListener("click", (e) => {
        e.preventDefault()
        
        const newAccount = document.getElementById("new-account");
        const loginBtn = document.getElementById("login");

        newAccount.classList.remove("d-none");
        newAccount.classList.add("d-flex");
        login.classList.remove("d-flex");
        login.classList.add("d-none");
    })

    document.getElementById("login-btn").addEventListener("click", (e) => {
        e.preventDefault()
        
        const newAccount = document.getElementById("new-account");
        const loginBtn = document.getElementById("login");

        newAccount.classList.remove("d-flex");
        newAccount.classList.add("d-none");
        login.classList.remove("d-none");
        login.classList.add("d-flex");
    })

</script>