function logout(){
    let xhr = new XMLHttpRequest();
    formData = new FormData();
    formData.append("logout", true);
    xhr.onreadystatechange = function() { 
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            document.location.href = ".";
        }
    }
    url = "/app/controllers/AuthController.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);
}

let searchInput = document.getElementById("search-input");
searchInput.addEventListener("keydown", function(event){
    if(event.key === "Enter"){
        event.preventDefault();
        location.href = "/public/search.php?search=" + this.value;
    }
});

function openNav(){
    document.getElementsByClassName("sidebar")[0].classList.add("active");

}

function closeNav(){
    document.getElementsByClassName("sidebar")[0].classList.remove("active");
}

function toggleDropdown(){
    document.getElementsByClassName("dropdown-items")[0].classList.toggle("active");
    var arrow = document.getElementsByClassName("arrow")[0];
    if(arrow.style.transform === "rotate(-135deg)"){
        arrow.style.transform = "rotate(45deg)";
        // arrow.style.-webkit-transform = "rotate(-135deg)";
    } else{
        arrow.style.transform = "rotate(-135deg)";
        // arrow.style.-webkit-transform = "rotate(45deg)";
    }
    // document.getElementsByClassName("arrow")[0].style.-webkit-transform = "rotate(135deg)";
}

