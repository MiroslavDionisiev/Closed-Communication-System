import { authenticate } from "../../utils.js";
import { authorize } from "../script.js";

window.onload = () => {
    authenticate();
    authorize();

    // <div class="user-info-wrapper">
    //     <ul class="user-info-keys">
    //     </ul>
    //     <ul class="user-info-values">
    //     </ul>
    // </div>

    let getUserBanner = (user) => {
        let banner = document.createElement("div");

        banner.innerHTML = `
        <a class="user-banner">
            <figure>
                <img src="img/img-user.png" alt="User profile picture">
                <figcaption class="user-name"></figcaption>
            </figure>
        </a>
        `;

        banner.querySelector(".user-name").innerHTML = user.userName;
        banner
            .querySelector("a")
            .addEventListener("click", (event) => openUserInfo(event.target));
        banner.querySelector("a").setAttribute("userId", user.userId);

        return banner.querySelector("a");
    };

    function fetchUsers() {
        fetch("/index.php/admin/users")
            .then((resp) => resp.json())
            .then((users) => {
                let list = document.getElementById("list-users");
                for (let user of users) {
                    list.appendChild(getUserBanner(user));
                }
            });
    }

    fetchUsers();

    function openUserInfo(userId) {
        console.log(userId);
        fetch(`/index.php/admin/users/${userId}`)
            .then((resp) => {
                if(resp.status != 200) {
                    alert("User doesn't exist");
                }
                return resp.json();
            })
            .then((user) => {});
    }
};
