import { authenticate } from "../../utils.js";
import { authorize } from "../script.js";

window.onload = () => {
    authenticate();
    authorize();

    let getMessageBanner = (data) => {
        let banner = document.createElement("div");
        banner.classList.add("message-banner");

        banner.innerHTML = `
            <p id="chat-room-name">${data.chatRoom.name}</p>
            <p id="sender-name">${data.user.name}</p>
            <p id="message-contents">${data.contents}</p>
            <button id="btn-approve" class="btn-green">Одобри</button>
            <button id="btn-deny" class="btn-red">Отхвърли</button>
        `;

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
