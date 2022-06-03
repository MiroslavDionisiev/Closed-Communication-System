import * as util from "../../utils.js";

(async () => {
    let user = await util.authenticate();

    // if (user.userRole !== USER_ROLES.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

    util.setHeader(user);

    function getChatRoomBanner(chatRoom) {}

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
                    chatRoomName             : document.getElementById("room-name").value,
                    chatRoomAvailabilityDate : document.getElementById("room-expiry-date").value,
                }),
            }

            fetch("/index.php/admin/chat-rooms")
        });

    fetch("/index.php/admin/chat-rooms")
        .then(async (resp) => {
            if (resp.status != 200) {
                throw await resp.json();
            }
            return resp.json();
        })
        .then((rooms) => {});
})();
