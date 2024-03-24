class Ve {
    constructor(from, where){
        from.addEventListener("input",e=>{
            const value = e.target.value;
            if(!value){
                //alert("aaaa sos re troll");
                return;
            }
            ajax("./city.php",{state : value})
            .then(json => {
                where.appendChild(htmlList(json, "city", `selecciona la ciudad en ${value}`))
            });
        })
    }
}