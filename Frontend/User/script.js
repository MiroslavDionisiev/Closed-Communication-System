async function getCurrentUser() {
    let user = await fetch('/index.php/account/is-authenticated', {
        method: 'GET'
    })
    .then(res => {
        if(res.status == 401) {
            window.loccation = "/Frontend/Login";
        }
        else {      
            return res.json();
        }
    })
    .then(data => {
        return data;
    });

    return user;
}

function redirectToChatRoom(chatRoomId) {
    let url = `/Frontend/ChatRoom?chatRoomId=${chatRoomId}`;
    window.location = url;
}

function getAllChatRooms() {
    fetch('/index.php/user/chat-rooms', {
        method: 'GET'
    })
    .then(res => {
        return res.json();
    })
    .then(data => {
        let ul = document.getElementById('chatRooms');
        data.forEach(element => {
            let li = document.createElement('li');

            let div = document.createElement('div');
            if (element['unreadMessages'] > 0) {
                div.setAttribute('class', 'unreadChatRoom');
            }
            else {
                div.setAttribute('class', 'chatRoom');
            }

            let ulRoomData = document.createElement('ul');
            ulRoomData.setAttribute('class', 'noStyle chatRoomData');
            let liName = document.createElement('li');
            liName.innerText = element['userChatRoom']['chatRoom']['chatRoomName'];
            let liRole = document.createElement('li');
            if (element['userChatRoom']['chatRoom']['userChatIsAnonymous'] === true) {
                liRole.innerText = "Роля в стаята: анонимен";
            }
            else {
                liRole.innerText = "Роля в стаята: неанонимен"; 
            }
            let liActive = document.createElement('li');
            liActive.innerText = 'Активна до: '.concat(element['userChatRoom']['chatRoom']['chatRoomAvailabilityDate']);
            ulRoomData.appendChild(liName);
            ulRoomData.appendChild(liRole);
            ulRoomData.appendChild(liActive);

            if (element['unreadMessages'] > 0) {
                let divUnread = document.createElement('div');
                divUnread.setAttribute('class', 'unreadMessage');

                div.appendChild(divUnread);
            }
            div.appendChild(ulRoomData);

            div.addEventListener("click", (e)=>{
                redirectToChatRoom(element['userChatRoom']['chatRoom']['chatRoomId']);
                e.preventDefault();
            });

            li.appendChild(div);
            ul.appendChild(li);  
        });
    });
}

(async () => {
    let user = await getCurrentUser();
    let username = document.getElementsByClassName('userName')[0];
    username.innerText = user['userName'];
    getAllChatRooms();
})();