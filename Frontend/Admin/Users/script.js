import { authenticate } from "../../utils.js";
import { authorize } from "../script.js";

window.onload = () => {
    authenticate();
    authorize();

    let getUserBanner = (user) => {
        let banner = document.createElement("div");
        banner.classList.add("user-banner");

        banner.innerHTML = `
            <img src="img/img-user.png" alt="User profile picture">
            <lu class="user-info">
            </lu>
        `;

        for (let key of Object.keys(user)) {
            if (key !== "id") {
                let li = document.createElement("li");
                li.innerHTML = `${user[key]}`;
                banner.querySelector("lu").appendChild(li);
            }
        }

        return banner;
    };

    let fetchUsers = () => {
        fetch("/index.php/admin/users")
            .then((resp) => resp.json())
            .then((users) => {
                let list = document.getElementById("list-users");
                for (let user of users) {
                    list.appendChild(getUserBanner(user));
                }
            });
    };

    fetchUsers();
};
