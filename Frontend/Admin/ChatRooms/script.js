import * as util from "../../utils.js";

(async () => {
    let user = await util.authenticate();

    // if (user.userRole !== USER_ROLES.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

    util.setHeader(user);

    function getChatRoomBanner(chatRoom) {

    }

    fetch("/index.php/admin/chat-rooms")
        .then(async (resp) => {
            if (resp.status != 200) {
                throw await resp.json();
            }
            return resp.json();
        })
        .then((rooms) => {

        });
})();
