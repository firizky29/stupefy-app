window.onload = function () {
    toggleSideBar();

    let xhr_premium_singer = new XMLHttpRequest();
    let xhr_subscription = new XMLHttpRequest();
    let subscribed_singer = [];
    let contents = document.getElementsByClassName("contents")[0];
    
    contents.innerHTML = "<tr> Unfortunately, there is no premium singer. </tr>";


    xhr_subscription.onreadystatechange = function () {
        if (xhr_subscription.readyState == 4 && xhr_subscription.status == 200) {
            console.log(xhr_subscription.responseText);
            let result = JSON.parse(xhr_subscription.responseText);
            if (result[0].length !== 0) {
                for (let i = 0; i < result[0].length; ++i) {
                    subscribed_singer.push(parseInt(result[0][i]['creator_id']));
                }
            }
            console.log(subscribed_singer);
            url_premium_singer = "http://localhost:3100/api/v1/singer/";

            xhr_premium_singer.open("GET", url_premium_singer, true);
            xhr_premium_singer.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr_premium_singer.send();

            xhr_premium_singer.onreadystatechange = function () {
                if (xhr_premium_singer.readyState == 4 && xhr_premium_singer.status == 200) {
                    console.log(xhr_premium_singer.responseText);
                    let result = JSON.parse(xhr_premium_singer.responseText);
                    if (result[0] !== "") {
                        contents.innerHTML = "";
                        for (let i = 1; i <= result.length; ++i) {
                            contents.innerHTML += generateSingers(i, result[i - 1], false);
                        }
                        console.log(result[0]);
                    }
                }
            }
        }
    }

    url_subscription = "/app/controllers/SubscriptionController.php?id=1";

    xhr_subscription.open("GET", url_subscription, true);
    xhr_subscription.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr_subscription.send();
}

function toggleSideBar() {
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

function generateSingers(i, res, isSubscribed) {
    let subscribeText = (isSubscribed) ? 'List of Songs' : 'Subscribe';
    let subscribeClass = (isSubscribed) ? 'song' : 'sub';
    let s = `<tbody class="content-entry">
    <tr>
        <td class = 'content-id'> 
            ${i} 
        </td>
        <td class = 'content-name'>
            ${res['name']}
        </td>
        <td class = 'content-subs'>
            <div class = 'button ${subscribeClass}'>
                ${subscribeText}
            </div>
        </td>
    </tr>
</tbody>`
    return s;
}