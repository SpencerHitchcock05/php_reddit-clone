<?php
// Messages functionality -- Required feature

    include_once("./templates/header.php");
?>


<div id="message-container" class="d-flex align-items-center flex-column">
    <form id="message-form" class="d-flex flex-column w-25">
        <label>To:</label>
        <input id="receiver" class="" type="text">
        <label>Message:</label>
        <textarea id="content"></textarea>
        <button id="send-message" class="btn btn-primary">Send Message</button>
    </form>
</div>

<div id="messages" class="d-flex justify-content-around">
    <div id="sent-messages" class="message h-100">
        
    </div>
    <div id="received-messages" class="message h-100">
    
    </div>
</div>

<script>

//asynchrously get all messages and add to the html
    function getMessages() {
        fetch("./api/messages.php")
        .then(resp => resp.json())
        .then(data => {

            console.log(data); 
            const sentMessages = document.getElementById("sent-messages");

            sentMessages.innerHTML = "";

            for (let i = 0; i < data.sent.length; i++) {
                const newMessage = document.createElement("div")
                newMessage.classList.add("border")
                newMessage.classList.add("border-dark")
                newMessage.classList.add("p-2")
                

                const name = document.createElement("p")
                const content = document.createElement("p")
                const date =document.createElement("p")

                content.innerText = data.sent[i].content;
                name.innerText = "to: " + data.sent[i].receiver_name;
                date.innerText = data.sent[i].timestamp;

                newMessage.appendChild(name);
                newMessage.appendChild(content);
                newMessage.appendChild(date);
                sentMessages.appendChild(newMessage);
            }

            const receivedMessages = document.getElementById("received-messages");

            receivedMessages.innerHTML = "";

            for (let i = 0; i < data.received.length; i++) {
                const newMessage = document.createElement("div")
                newMessage.classList.add("border")
                newMessage.classList.add("border-dark")
                newMessage.classList.add("p-2")
                

                const name = document.createElement("p")
                const content = document.createElement("p")
                const date =document.createElement("p")

                content.innerText = data.received[i].content;
                name.innerText = "from: " + data.received[i].sender_name;
                date.innerText = data.received[i].timestamp;

                newMessage.appendChild(name);
                newMessage.appendChild(content);
                newMessage.appendChild(date);
                receivedMessages.appendChild(newMessage);
            }

        })
    }
    
    getMessages()

    const myInterval = setInterval(() => {getMessages()}, 5000);
    
//send a message asyncrously and store in database
    async function sendMessage() {

        const receiverElem = document.getElementById("receiver")
        const contentElem = document.getElementById("content")

        const receiver = receiverElem.value
        const content = contentElem.value


        fetch("./api/messages.php", {
        headers: {"Content-Type" : "application/json"},
        method: "POST",
        body: JSON.stringify({content: content, receiver: receiver})
        })
        .then(resp => resp.text())
        .then(data => {
            getMessages()
        })
        .catch(err => {console.log(err)})

        receiverElem.value = ""
        contentElem.value = ""

        
    }

    document.getElementById("send-message").addEventListener("click",  (e) => {
        e.preventDefault()

        sendMessage();
    })

    



</script>


<?php

    

    include_once("./templates/footer.php");
?>