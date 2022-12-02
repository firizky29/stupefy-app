window.onload = function () {
    toggleSideBar();

    let xhr_subscription = new XMLHttpRequest();
    let subscribed_singer = new Set();
    
    xhr_subscription.onreadystatechange = function() { 
        if (xhr_subscription.readyState == 4 && xhr_subscription.status == 200) {
            console.log(xhr_subscription.responseText);
            let result = JSON.parse(xhr_subscription.responseText);
            // console.log("Hello1");
            // console.log(result);
            if (result[0].length !== 0) {
                for(let i=0; i<result[0].length; ++i){
                    subscribed_singer.add(parseInt(result[0][i]['creator_id']));
                }
            }
            console.log("HI:")
            console.log(subscribed_singer);
            fetchPremiumSinger1(subscribed_singer);
        }
    }

    url_subscription = "/app/controllers/SubscriptionController.php?status=ACCEPTED";

    xhr_subscription.open("GET", url_subscription, true);
    xhr_subscription.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr_subscription.send();
}

function fetchPremiumSinger1(subscribedSinger) {
    let xhr_pending = new XMLHttpRequest();
    let url = "/app/controllers/SubscriptionController.php?status=PENDING";
    let pendingSinger = new Set();
    xhr_pending.onreadystatechange = function() {
        if (xhr_pending.readyState == 4 && xhr_pending.status == 200) {
            console.log(xhr_pending.responseText);
            let result = JSON.parse(xhr_pending.responseText);
            if (result[0].length !== 0) {
                for(let i=0; i<result[0].length; ++i){
                    pendingSinger.add(parseInt(result[0][i]['creator_id']));
                }
            }
            fetchPremiumSinger2(subscribedSinger, pendingSinger);
        }
    }
    xhr_pending.open("GET", url, true);
    xhr_pending.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr_pending.send();
}

function fetchPremiumSinger2(subscribedSinger, pendingSinger) {
    let xhr_premium_singer = new XMLHttpRequest();
    let url_premium_singer = "http://localhost:3100/api/v1/singer/";

    xhr_premium_singer.open("GET", url_premium_singer, true);
    xhr_premium_singer.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr_premium_singer.send();

    xhr_premium_singer.onreadystatechange = function() { 
        if (xhr_premium_singer.readyState == 4 && xhr_premium_singer.status == 200) {
            let contents = document.getElementsByClassName("contents")[0];
            console.log(xhr_premium_singer.responseText);
            let result = JSON.parse(xhr_premium_singer.responseText);
            contents.innerHTML = "<tr> Unfortunately, there is no premium singer. </tr>";
            if (result[0] !== "") {
                contents.innerHTML = "";
                for(let i=1; i<=result.length; ++i){
                    // console.log(result[i-1]['id']);
                    if(subscribedSinger.has(parseInt(result[i-1]['user_id']))){
                        console.log(result[i-1]);
                        contents.innerHTML += generateSingers(i, result[i-1], "ACCEPTED");
                    } else if(pendingSinger.has(parseInt(result[i-1]['user_id']))){
                        contents.innerHTML += generateSingers(i, result[i-1], "PENDING");
                    } else {
                        contents.innerHTML += generateSingers(i, result[i-1], "canSubscribe"); //rejected or nothing
                    }
                }
                console.log(result[0]);
            }
        }
    }
    
}

function toggleSideBar(){
    let sidebarActive = document.getElementsByClassName("active")[0];
    sidebarActive.classList.remove("active");
    sidebarActive.children[0].src = "/public/img/icons-" + sidebarActive.id + "-grey.png";

    let sidebar = document.getElementById("premium-singer");
    sidebar.classList.add("active");
    sidebar.children[0].src = "/public/img/icons-" + sidebar.id + ".png";
}

function getDetailedSong($id) {
    window.location.href = "/public/detail-song.php?id=" + $id;
}

function generateSingers(i, res, status){
    let subscribeText;
    if(status==="ACCEPTED"){
        subscribeText = 'List of Songs';
    } else if(status==="PENDING"){
        subscribeText = 'Pending';
    } else {
        subscribeText = 'Subscribe';
    }
    let subscribeClass;
    if(status==="ACCEPTED"){
        subscribeClass = 'song';
    } else if(status==="PENDING"){
        subscribeClass = 'sub disabled';
    } else {
        subscribeClass = 'sub';
    }
    let subscribeMethod;
    if(status==="ACCEPTED"){
        subscribeMethod = 'getPremiumSong('+res['user_id']+')'; 
    } else {
        subscribeMethod = 'subscribe(this,'+res['user_id']+')';
    }
    let s = `<tbody class="content-entry">
    <tr>
        <td class = 'content-id'> 
            ${i} 
        </td>
        <td class = 'content-name'>
            ${res['name']}
        </td>
        <td class = 'content-subs'>
            <div class = 'button ${subscribeClass}' onclick='${subscribeMethod}'>
                ${subscribeText}
            </div>
        </td>
    </tr>
</tbody>`
return s;
}

function subscribe(elem, creator_id) {
    let xhr = new XMLHttpRequest();
    let url = "/app/controllers/SubscriptionController.php";
    formData = new FormData();
    formData.append("subscribe", true);
    formData.append("creator_id", creator_id);
    xhr.onreadystatechange = function() { 
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            let result = JSON.parse(xhr.responseText);
            if (result[0]["SubscribeResponse"]["data"] == 1) {
                // perlu ganti button langsung ga
                console.log(elem);
                elem.classList.add("disabled");
                elem.innerHTML = "Pending";
            }
        }
    }
    xhr.open("POST", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);
}

function getPremiumSong(creator_id) {
    window.location.href = "/public/premium-song.php?id=" + creator_id;
}