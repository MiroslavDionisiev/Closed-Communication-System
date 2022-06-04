import * as util from "../../utils.js";
import * as admin from "../utils.js";

(async () => {
    let user = await util.authenticate();
    // admin.authorize(user);
    util.setHeader(user);

    function getChatRoomBanner(chatRoom) {}

    async function getAllChatRooms() {
        return fetch("/index.php/admin/chat-rooms")
            .then(async (resp) => {
                if (resp.status != 200) {
                    throw await resp.json();
                }
                return resp.json();
            })
            .then((rooms) => {})
            .catch((err) => console.log(err.error));
    }

    function getUserRow(user) {
        let row = document.createElement("li");
        row.id = user.userId;
        row.innerHTML = `
            <div>${user.userName}</div>
            <div>${user.userIdentity}</div>
            <div>
                <label for="input">Анонимен</label>
                <input type="checkbox" class="is-anonymous">
            </div>
        `;

        let isAnonymous = row.querySelector("input");
        isAnonymous.addEventListener("change", (event) => {
            if (isAnonymous.checked) {
                row.classList.add("anonymous");
            } else {
                row.classList.remove("anonymous");
            }
        });
        isAnonymous.checked = true;
        row.classList.add("anonymous");

        return row;
    }

    function closeSelectUsers() {
        let btn = document.querySelector("#add-users-btn");
        let select = document.querySelector("#select-users");
        btn.classList.remove("dropped");
        select.style.width = "0";
        select.style.height = "0";
        select.style.opacity = 0;
        while (select.lastChild) {
            select.removeChild(select.lastChild);
        }
    }

    document
        .getElementById("create-room-btn")
        .addEventListener("click", (event) => {
            let roomName = document.getElementById("room-name");

            if (roomName.validity.valueMissing) {
                roomName.setCustomValidity("Името е задължителнo.");
            } else {
                roomName.setCustomValidity("");
            }
            roomName.reportValidity();
        });

    document
        .getElementById("room-create-form")
        .addEventListener("submit", (event) => {
            event.preventDefault();

            let options = {
                method: "POST",
                body: JSON.stringify({
                    chatRoomName: document.getElementById("room-name").value,
                    chatRoomAvailabilityDate:
                        document.getElementById("room-expiry-date").value === ""
                            ? null
                            : document.getElementById("room-expiry-date").value,
                    userChats: [
                        ...document.querySelectorAll(".users-data-list li"),
                    ].map((li) => {
                        return {
                            userId: li.id,
                            userChatIsAnonymous:
                                li.querySelector(".is-anonymous").checked,
                        };
                    }),
                }),
            };

            fetch("/index.php/admin/chat-rooms", options)
                .then(async (resp) => {
                    if (resp.status >= 200 && resp.status < 400) {
                        util.popAlert((await resp.json()).message);

                        document.getElementById("room-name").value = "";
                        let usersList = document.querySelector('.users-data-list');
                        while(usersList.lastChild) {
                            usersList.removeChild(usersList.lastChild);
                        }
                    } else {
                        throw await resp.json();
                    }
                })
                .catch((err) =>
                    util.popAlert(err.error, util.ALERT_TYPE.DANGER)
                );
        });

    document
        .getElementById("add-users-btn")
        .addEventListener("click", async (event) => {
            let btn = event.currentTarget;
            let select = document.querySelector("#select-users");

            if (!btn.classList.contains("dropped")) {
                btn.classList.add("dropped");
                select.style.width = "50vw";
                select.style.height = "fit-content";
                select.style.opacity = 1;

                let users = await admin.getAllUsers();
                for (let user of users) {
                    let banner = admin.getUserBanner(user);
                    banner.style.position = "relative";

                    let checkbox = document.createElement("input");
                    checkbox.type = "checkbox";
                    checkbox.classList.add("select-user-checkbox");

                    let usersDataList =
                        document.querySelector(".users-data-list");
                    if (document.getElementById(`${user.userId}`) != null) {
                        checkbox.checked = true;
                    }

                    checkbox.addEventListener("change", (event) => {
                        if (checkbox.checked) {
                            usersDataList.appendChild(getUserRow(user));
                        } else {
                            usersDataList.removeChild(
                                document.getElementById(`${user.userId}`)
                            );
                        }
                    });

                    banner.appendChild(checkbox);
                    select.appendChild(banner);
                }
            } else {
                closeSelectUsers();
            }
        });

    document.addEventListener("click", (event) => {
        if (
            !event.target.matches("#select-users, #select-users *, .curtain") &&
            !event.target.matches("#add-users-btn")
        ) {
            closeSelectUsers();
        }
    });
})();
