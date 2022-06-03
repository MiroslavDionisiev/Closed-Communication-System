import * as util from "../utils.js";

function displayNewMessages(user) {
    let chatRoomId = window.location.href.split("=")[1];
    if (
        document.getElementsByClassName("sender")[
            document.getElementsByClassName("sender").length - 1
        ] === undefined
    ) {
        console.log("skip");
        return;
    }
    let [first, ...rest] = document
        .getElementsByClassName("sender")
        [
            document.getElementsByClassName("sender").length - 1
        ].textContent.split(":");
    let date = rest.join(":");

    fetch(
        `/index.php/user/chat-rooms/${chatRoomId}/messages?lastTimestamp=${date}`,
        {
            method: "GET",
        }
    )
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            populateChatRoom(data, user);
        });

    // work in progress
    // let chatBox = document.getElementsByClassName("chatBox")[0];
    // chatBox.scrollTop = chatBox.scrollHeight;
}

function displayAllMessages(user) {
    let chatRoomId = window.location.href.split("=")[1];
    fetch(`/index.php/user/chat-rooms/${chatRoomId}/messages`, {
        method: "GET",
    })
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            populateChatRoom(data, user);
        });
}

function populateChatRoom(data, user) {
    data.forEach((element) => {
        let chatBox = document.getElementsByClassName("chatBox")[0];

        let messageSender = document.createElement("div");
        if (
            element["user"] != undefined &&
            element["user"]["userId"] === user.userId
        ) {
            messageSender.setAttribute("class", "byYou");
        } else {
            messageSender.setAttribute("class", "byOthers");
        }

        let messageDiv = document.createElement("div");
        messageDiv.setAttribute("class", "message");

        let senderP = document.createElement("p");
        senderP.setAttribute("class", "sender");
        if (element["user"] == undefined) {
            senderP.innerText = `Анонимен: ${element["messageTimestamp"]}`;
        } else {
            senderP.innerText = `${element["user"]["userName"]}: ${element["messageTimestamp"]}`;
        }

        let messageP = document.createElement("p");
        messageP.setAttribute("class", "content");
        messageP.innerText = element["messageContent"];

        messageDiv.appendChild(senderP);
        messageDiv.appendChild(messageP);
        messageSender.appendChild(messageDiv);
        chatBox.appendChild(messageSender);
    });
}

function sendMessage() {
    let message = document.getElementById("messageInput").value;
    let chatRoomId = window.location.href.split("=")[1];

    fetch(`/index.php/user/chat-rooms/${chatRoomId}/messages`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ message }),
    });
    document.getElementById("messageInput").value = "";
}

(async () => {
    let user = await util.authenticate();
    util.setHeader(user);
    displayAllMessages(user);
    setInterval(displayNewMessages, 1000, user);

    const sendMessageForm = document.getElementById("message-input");

    sendMessageForm.addEventListener("submit", (e) => {
        sendMessage();
        e.preventDefault();
    });
})();

