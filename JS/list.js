function htmlList(
    array = [],
    name = "select", 
    title = null, 
    selected = null,
    sameValueName = false
    ){
    const select = document.createElement("select");
    select.name = name;
    if(title){
        const option = document.createElement("option");
        option.innerText = `--- ${title} ---`;
        select.appendChild(option);
    }
    array.forEach((value, idx) => {
        const option = document.createElement("option");
        if(value === selected)option.setAttribute("selected","selected");
        option.value = sameValueName ? value : idx;
        option.innerText = value;
        select.appendChild(option);
    });
    return select;
}