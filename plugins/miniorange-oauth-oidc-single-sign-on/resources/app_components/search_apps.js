//Default Apps Filter
function mo_oauth_client_default_apps_input_filter() {
    var input, filter, ul, li, a, i;
    var counter = 0;
    input = document.getElementById("mo_oauth_client_default_apps_input");
    filter = input.value.toUpperCase();
    ul = document.getElementById("mo_oauth_client_default_apps");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.split('<br>')[1].toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
            counter++;
        }
        if(counter>=li.length) {
            document.getElementById("mo_oauth_client_search_res").innerHTML = "<p class='lead muted'>No Applications Found In This Category.</p>";
        } else {
            document.getElementById("mo_oauth_client_search_res").innerHTML = "";
        }
    }
}

function showClientSecret(){
    var field = document.getElementById("mo_oauth_client_secret");
    var show_button = document.getElementById("show_button");
    if(field.type == "password"){
        field.type = "text";
        show_button.className = "fa fa-eye-slash";
    }
   else{
        field.type = "password";
        show_button.className = "fa fa-eye";
    }
}