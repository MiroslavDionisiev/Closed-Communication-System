import * as util from "../utils.js";

(async () => {
    let user = await util.authenticate();

    // if (user.userRole !== USER_ROLES.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

    util.setHeader(user);
})();
