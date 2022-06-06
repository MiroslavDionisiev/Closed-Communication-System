import * as util from "../../utils.js";
import * as admin from "../utils.js";

(async () => {
    let user = await util.authenticate();
    admin.authorize(user);
    util.setHeader(user);

    let users = await admin.getAllUsers();
    let list = document.getElementById("list-users");
    for (let user of users) {
        list.appendChild(admin.getUserBanner(user));
    }
})();
