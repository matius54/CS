class Ve {
    constructor(from, where){
        const iter = (dup, call, opt = []) => {
            if(!dup) return;
            dup.forEach(e => e[call](...opt));
        };
        from.addEventListener("input",e=>{
            const value = e.target.value;
            const name = "city";
            const duplicated = document.getElementsByName(name);
            if(!value){
                iter(duplicated, "remove");
                return;
            }
            iter(duplicated, "setAttribute", ["disabled","disabled"]);
            ajax("./city.php",{state : value})
            .then(json => {
                iter(duplicated, "remove");
                where.appendChild(htmlList(json, name, `selecciona la ciudad en ${value}`))
            });
        })
    }
}