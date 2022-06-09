import * as util from "../utils.js";

function redirectToChatRoom(chatRoomId) {
    let url = `/ChatRoom?chatRoomId=${chatRoomId}`;
    window.location = util.urlFrontend(url);
}

function setAnonimity(chatRoomId, isAnonymouse) {
    fetch(util.urlBackend(`/user/chat-rooms`), {
        method: 'PATCH',
        body: JSON.stringify({ chatRoomId, isAnonymouse}),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
    });
}

function showAnonimityPrompt(chatRoom, chatRoomId) {
    let rolePickerSection = document.createElement('section');
    rolePickerSection.setAttribute('class', 'rolePicker');

    let sectionP = document.createElement('p');
    sectionP.innerText = 'Искаш ли да станеш неанонимен?';
            
    let userChoiceDiv = document.createElement('div');
    userChoiceDiv.setAttribute('class', 'userChoice');

    let approveBtn = document.createElement('button');
    approveBtn.setAttribute('class', 'approve');
    approveBtn.innerText = 'Да';
    approveBtn.addEventListener("click", (e)=>{
        setAnonimity(chatRoomId, false);
        getAllChatRooms();
        e.preventDefault();
    });

    let declineBtn = document.createElement('button');
    declineBtn.setAttribute('class', 'decline');
    declineBtn.innerText = 'Не';
    declineBtn.addEventListener("click", (e)=>{
        setAnonimity(chatRoomId, true);
        getAllChatRooms();
        e.preventDefault();
    });

    userChoiceDiv.appendChild(approveBtn);
    userChoiceDiv.appendChild(declineBtn);
    rolePickerSection.appendChild(sectionP);
    rolePickerSection.appendChild(userChoiceDiv);
    chatRoom.appendChild(rolePickerSection);
}

function getAllChatRooms() {
    fetch(util.urlBackend('/user/chat-rooms'), {
        method: 'GET'
    })
    .then(res => {
        return res.json();
    })
    .then(data => {
        let ul = document.getElementById('chatRooms');
        ul.innerHTML = '';
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
            if (element['userChatRoom']['userChatIsAnonymous'] === true) {
                liRole.innerText = "Роля в стаята: анонимен";
            }
            else {
                liRole.innerText = "Роля в стаята: неанонимен"; 
            }
            let liActive = document.createElement('li');
            liActive.innerText = 'Активна до: '.concat(element['userChatRoom']['chatRoom']['chatRoomAvailabilityDate'] ?? 'няма срок');
            ulRoomData.appendChild(liName);
            ulRoomData.appendChild(liRole);
            ulRoomData.appendChild(liActive);

            div.appendChild(ulRoomData);
            if (element['unreadMessages'] > 0) {
                let divUnread = document.createElement('div');
                divUnread.setAttribute('class', 'unreadMessage');
                divUnread.innerText = `Непрочетени съобщения: ${element['unreadMessages']}`;
                div.appendChild(divUnread);
            }

            let chatRoomAvailabilityDate = Date.parse(
                element['userChatRoom']['chatRoom']['chatRoomAvailabilityDate']
            );
            let now = new Date().getTime();

            div.addEventListener("click", (e)=>{
                if (
                    now > chatRoomAvailabilityDate
                    && element['userChatRoom']['userChatHasResponded'] == false 
                    && element['userChatRoom']['userChatIsAnonymous'] == true
                ) {
                    util.popAlert(
                        "Трябва да избереш дали да останеш анонимен",
                        util.ALERT_TYPE.WARNING
                    );
                }
                else {
                    redirectToChatRoom(element['userChatRoom']['chatRoom']['chatRoomId']);
                }
                e.preventDefault();
            });

            if (
                now > chatRoomAvailabilityDate
                && element['userChatRoom']['userChatHasResponded'] == false 
                && element['userChatRoom']['userChatIsAnonymous'] == true
            ) {
                showAnonimityPrompt(div, element['userChatRoom']['chatRoom']['chatRoomId']);
            }

            li.appendChild(div);
            ul.appendChild(li);  
        });
    });
}

(async () => {
    let user = await util.authenticate();
    util.setHeader(user);
    getAllChatRooms();
})();
