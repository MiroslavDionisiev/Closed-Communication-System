import * as util from "../../utils.js";

(async () => {
    let user = await util.authenticate();
    // admin.authorize(user);
    util.setHeader(user);

    let getMessageBanner = (msg) => {
        let banner = document.createElement("tr");
        banner.setAttribute("msg-id", msg.messageId);

        banner.innerHTML = `
            <td class="chat-room-name"><div>${msg.chatRoom.chatRoomName}</div></td>
            <td class="sender-name"><div>${msg.user.userName}</div></td>
            <td class="message-contents"><div>${msg.messageContent}</div></td>
            <td>
                <button type="button" class="btn-approve">Одобри</button>
                <button type="button" class="btn-deny">Отхвърли</button>
            </td>
        `;

        banner.querySelector(".btn-deny").addEventListener("click", (event) => {
            let node = event.currentTarget.parentNode.parentNode;
            let msgId = node.getAttribute("msg-id");

            let options = {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                },
            };

            fetch(`/index.php/admin/disabled-messages/${msgId}`, options)
                .then(async (resp) => {
                    if (resp.status >= 200 && resp.status < 400) {
                        node.parentNode.removeChild(node);
                        util.popAlert((await resp.json()).message);
                    } else {
                        throw await resp.json();
                    }
                })
                .catch((err) => {
                    util.popAlert(err.error);
                });
        });

        banner
            .querySelector(".btn-approve")
            .addEventListener("click", (event) => {
                let node = event.currentTarget.parentNode.parentNode;
                let msgId = node.getAttribute("msg-id");

                let options = {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        messageIsDisabled: false,
                    }),
                };
                fetch(
                    `/index.php/admin/disabled-messages/${msgId}`,
                    options
                ).then(async (resp) => {
                    if (resp.status >= 200 && resp.status < 400) {
                        node.parentNode.removeChild(node);
                        util.popAlert((await resp.json()).message);
                    }
                });
            });

        return banner;
    };

    let fetchMessages = () => {
        fetch("/index.php/admin/disabled-messages")
            .then((resp) => resp.json())
            .then((messages) => {
                let list = document.getElementById("list-disabled-messages");
                for (let msg of messages) {
                    list.querySelector("tbody").appendChild(
                        getMessageBanner(msg)
                    );
                }
            });
    };

    fetchMessages();
})();
