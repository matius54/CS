async function ajax(url,json){
    const response = await fetch(url,{
        method: "post",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Accept": "application/json"
        },
        body: JSON.stringify(json)
    });
    return await response.json();
}