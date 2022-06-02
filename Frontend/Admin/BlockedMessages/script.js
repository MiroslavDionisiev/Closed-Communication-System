import { authenticate } from "../../utils.js";
import { authorize } from "../script.js";

window.onload = () => {
    authenticate();
    authorize();

    let getMessageBanner = (msg) => {
        let banner = document.createElement("div");
        banner.classList.add("message-banner");
        banner.setAttribute("msg-id", msg.id);

        banner.innerHTML = `
            <p id="sender-name">${msg.user.name}</p>
            <p id="message-contents">${msg.content ?? ""}</p>
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
                    id: event.target.parentNode.getAttribute("msg-id"),
                }),
            };
            fetch("/index.php/admin/disabled-messages", options).then((resp) =>
                console.log(resp)
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
                        id: event.target.parentNode.getAttribute("msg-id"),
                        isDisabled: false,
                    }),
                };
                fetch("/index.php/admin/disabled-messages", options).then(
                    (resp) => console.log(resp.text())
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
