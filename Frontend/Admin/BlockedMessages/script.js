import { authenticate, GLOBALS } from "../../utils.js";

window.onload = async () => {
    // let user = await authenticate();

    // if (user.userRole !== GLOBALS.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

    let getMessageBanner = (msg) => {
        let banner = document.createElement("div");
        banner.classList.add("message-banner");
        banner.setAttribute("msg-id", msg.messageId);

        banner.innerHTML = `
            <p id="chat-room-name">${msg.chatRoom.chatRoomName}</p>
            <p id="sender-name">${msg.user.userName}</p>
            <p id="message-contents">${msg.messageContent ?? ""}</p>
            <button id="btn-approve" class="btn-green">Одобри</button>
            <button id="btn-deny" class="btn-red">Отхвърли</button>
        `;

        banner.querySelector("#btn-deny").addEventListener("click", (event) => {
            let options = {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    messageId: event.target.parentNode.getAttribute("msg-id"),
                }),
            };
            fetch("/index.php/admin/disabled-messages", options).then(
                (resp) => {
                    if (resp.ok) {
                        event.target.parentNode.parentNode.removeChild(
                            event.target.parentNode
                        );
                    }
                }
            );
        });

        banner
            .querySelector("#btn-approve")
            .addEventListener("click", (event) => {
                let options = {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        messageId: event.target.parentNode.getAttribute("msg-id"),
                        messageIsDisabled: false,
                    }),
                };
                fetch("/index.php/admin/disabled-messages", options).then(
                    (resp) => {
                        if (resp.ok) {
                            event.target.parentNode.parentNode.removeChild(
                                event.target.parentNode
                            );
                        }
                    }
                );
            });

        return banner;
    };

    let fetchMessages = () => {
        fetch("/index.php/admin/disabled-messages")
            .then((resp) => resp.json())
            .then((messages) => {
                let list = document.getElementById("list-disabled-messages");
                for (let msg of messages) {
                    list.appendChild(getMessageBanner(msg));
                }
            });
    };

    fetchMessages();
};
