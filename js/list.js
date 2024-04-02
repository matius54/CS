function htmlList(el, array = [], msg){
    el.querySelectorAll("option").forEach(e => e.remove());
    if(typeof(msg) === "string"){
        const opt = document.createElement("option");
        opt.innerText = `--- ${msg} ---`;
        opt.setAttribute("value","");
        el.appendChild(opt);
    }
    array.forEach(value => {
        const option = document.createElement("option");
        option.setAttribute("value",value);
        option.innerText = value;
        el.appendChild(option);
    });
}