const form = document.getElementById('form');
const subBTN = document.getElementById('createBTN');
const canBTN = document.getElementById('cancelBTN');
const nameINP = document.getElementById('nameINPUT');
const platINP = document.getElementById('platINPUT');
const tbody = document.getElementById('tbody');
const frmSbtn = document.getElementById('formSHOWbtn');
const JSONpath = "../backend/data/games.json";
const APIpath = "../backend/api.php";


frmSbtn.addEventListener("click", ()=>{
        form.hidden = false;
})

canBTN.addEventListener("click", ()=>{
    form.hidden = true;
    nameINP.value = "";
    platINP.value = "";
})

form.addEventListener("submit",(e)=>{
    e.preventDefault();
    
    data = [
        nameINP.value,
        platINP.value
    ];

    fetch(APIpath, {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify(data)
    })
})



function fetchItems(){
    fetch(JSONpath)
    .then((Response)=> Response.json())
    .then((games)=>{
        games.forEach(game => {
            
        
        let HTML =
            `
            <tr>
                <td class="border-1 px-5 py-4">${game.name}</td>
                <td class="border-1 px-5 py-4">${game.platform}</td>
            </tr>
            `;

        tbody.insertAdjacentHTML("beforeend", HTML);
    })
    });
}

fetchItems();
