const form = document.getElementById('form');
const showbtn = document.getElementById('btn-show-form');
const sectionForm = document.getElementById('section-form');
const nameINP = document.getElementById('game-name');
const platINP = document.getElementById('game-platform');
const cancel = document.getElementById('btn-cancel-form');
const tbody = document.getElementById('table-games-body');
const submitbtn = document.getElementById('submitbtn');
const successT = document.getElementsByClassName('successT');
const APIpath = "../backend/api.php";


function isLoading(isLoading){
    if(isLoading){
        submitbtn.disabled = isLoading
        submitbtn.textContent = "Saving..."
    }
    if(!isLoading){
        submitbtn.disabled = isLoading
        submitbtn.textContent = "Save"
    }
}


class API{
    get(){
        fetch(APIpath)
        .then(res => res.json())
        .then(games =>{
            tbody.innerHTML = '';
            games.forEach(game => {
                tbody.insertAdjacentHTML("beforeend", `
                    <tr>
                        <td>${game.id}</td>
                        <td>${game.name}</td>
                        <td>${game.platform}</td>
                        <td><button>EDIT</button><button>DELETE</button></td>
                    </tr>
                    `);
            });
        })
    }

    post(){
        setTimeout(() => {
            fetch(APIpath, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                name: nameINP.value,
                platform: platINP.value
            })
        })
        .then(res => res.json())
        .then(result => console.log(result))
        .catch(error => console.error(error))
        .finally(()=>{
            isLoading(false);
            this.get();
            form.reset();
            successT.ClassList.remove("hidden");
            setTimeout(() => {
                successT.ClassList.add("hidden")
            }, 2000);
        });
        }, 3000);
    }
}
APIobj = new API;
document.addEventListener("DOMContentLoaded",()=>{
    APIobj.get();
})
showbtn.addEventListener("click", ()=>{
    sectionForm.hidden = false;
})
cancel.addEventListener("click", ()=>{
    sectionForm.hidden = true;
    form.reset();
})
form.addEventListener("submit", (e)=>{
    e.preventDefault();
    isLoading(true);
    APIobj.post();
})