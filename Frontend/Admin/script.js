import * as util from "../utils.js";
import * as admin from "./utils.js";

(async () => {
    let user = await util.authenticate();
    admin.authorize(user);
    util.setHeader(user);
})();
