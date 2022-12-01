
function toggleSideBar() {
    let sidebarActive = document.getElementsByClassName("active")[0];
    sidebarActive.classList.remove("active");
    sidebarActive.children[0].src = "/public/img/icons-" + sidebarActive.id + "-grey.png";

    let sidebar = document.getElementById("premium-singer");
    sidebar.classList.add("active");
    sidebar.children[0].src = "/public/img/icons-" + sidebar.id + ".png";
}

window.onload = function () {
    toggleSideBar();
    let contents = document.getElementsByClassName("contents")[0];
    let xhr = new XMLHttpRequest();
    contents.innerHTML = "<tr> Unfortunately, there is no song from this singer. </tr>";
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const singerId = urlParams.get('id');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            let result = JSON.parse(xhr.responseText);
            // console.log(result);
            if (result.length > 0 && result[0] !== "") {
                contents.innerHTML = "";
                for (let i = 1; i <= result.length; ++i) {
                    contents.innerHTML += generateSongs(i, result[i - 1]);
                }
                // console.log(result[0]);
            }
        }
    }

    url = "http://localhost:3100/api/v1/singer/song/"+singerId;
    xhr.open("GET", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send();



    // retrieving artist's name
    let title = document.getElementsByClassName("container-title")[0];
    title.innerHTML = "Premium Songs by ??";

    let xhr2 = new XMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState == 4 && xhr2.status == 200) {
            // console.log(xhr2.responseText);
            let result = JSON.parse(xhr2.responseText);

            // console.log(result);
            // console.log(result);
            if (result && result !== "") {
                title.innerHTML = "Premium Songs by " + result.name;
                // console.log(result.name);
            }
        }
    }

    url = "http://localhost:3100/api/v1/singer/"+singerId;
    xhr2.open("GET", url, true);
    xhr2.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr2.send();
}

function generateSongs(i, res, isSubscribed) {
    let s = `<tbody class="content-entry">
    <tr>
        <td class = 'content-id'> 
            ${i} 
        </td>
        <td class = 'content-name'>
            ${res['Judul']}
        </td>
        <td class = 'content-subs'>
            <div class = 'button play'>
                Play
            </div>
        </td>
    </tr>
</tbody>`
    return s;
}