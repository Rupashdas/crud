document.addEventListener('DOMContentLoaded', function(){
    var links = document.querySelectorAll(".delete");
    for(i = 0; i<links.length;i++){
        links[i].addEventListener('click', function(){
            if(!confirm("Are you sure?")){
                e.preventDefault();
            }
        });
    }
});