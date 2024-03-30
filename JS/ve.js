class Ve {
    constructor(from, where){
        from.addEventListener("input",e=>{
            const value = e.target.value;
            if(!value){
                where.setAttribute("disabled","disabled");
                htmlList(where, []);
                return;
            }
            where.setAttribute("disabled","disabled");
            ajax("./city.php",{state : value})
            .then(json => {
                where.removeAttribute("disabled");
                htmlList(where, json, `selecciona la ciudad en ${value}`);
            });
        })
    }
}