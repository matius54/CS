class Validate {
    constructor(){
        document.querySelectorAll("form").forEach(form => {
            form.querySelectorAll("input").forEach((e) => this.addListeners(e));
            form.querySelectorAll("list").forEach((e) => this.addListeners(e));
            form.querySelectorAll("textarea").forEach((e) => this.addListeners(e));
        });
    }
    listeners(element,listener = this.validate){
        let same;
        if(same = this.same(element)){
                this.addListeners(element,(e)=>{
                const element = e.target;
                this.same({target: element});
            });
        }else{
            element.addEventListener("click",listener);
            element.addEventListener("input",listener);
            element.addEventListener("blur",listener);
        }
    }
    addListeners(element,listener = this.validate){
        element.addEventListener("click",listener);
        element.addEventListener("input",listener);
        element.addEventListener("blur",listener);
    }
    same(element){
        const value = element.value;
        let same;
        if(typeof (same = element.getAttribute("same")) === "string"){
            const form = element.closest("form");
            const el2 = form.querySelector(`input[name="${same}"]`);
            if(el2 && value){
                validate.updateDOM(element,(element.value === el2.value))
                return el2;
            }
        }
    }
    validate(event){
        //valores de configuracion
        const MATCH = "match";
        const DIVIDER = "_";
        const LATIN = /^[\wñáéíóúÑÁÉÍÓÚ]+( [\wñáéíóúÑÁÉÍÓÚ]+)?$/;
        const regex = {
            text : /[A-Za-z ñáéíóúÑÁÉÍÓÚ]+/g,
            username : /^\w{1,64}/,
            password : /^.{8,}$/,
            firstname : LATIN,
            lastname : LATIN,
            email : /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/,
            birthdate : /^(20|19)\d{2,2}-[0-1]\d-[0-3]\d$/,
            rif : /^[J](-| )?\d{5,20}$/i,
            ci : /^[VE](-| )?\d{5,15}$/i,
            phone : /^(\+58 |0)(414|424|416|241) ?\d{7,7}$/
        };
        //constantes y variables
        const element = event.target;
        //if(validate.same(element)) return;
        const value = element.value;
        let key = element.type;
        let valid;
        let test;
        //validacion
        if(element.id) key = element.id.split(DIVIDER)[0];
        if(element.name) key = element.name.split(DIVIDER)[0];
        if((test = regex[key]) && value){
            if(typeof element.getAttribute(MATCH) === "string"){
                const val = value.match(test);
                if(val){
                    element.value = val.join("");
                    valid = true;
                }else{
                    element.value = "";
                }
            }else{
                valid = test.test(value);
            }
        }
        //actualizacion del dom
        validate.updateDOM(element,valid);
    }
    updateDOM = (element, result) => {
        const VALID = "is-valid";
        const INVALID = "is-invalid";
        const classL = element.classList;
        if(result === true){
            classL.add(VALID);
            classL.remove(INVALID);
        }else if(result === false){
            classL.remove(VALID);
            classL.add(INVALID);
        }else{
            classL.remove(VALID);
            classL.remove(INVALID);
        }
    }
}

//TODO: validar formulario y preventDefault()
const validate = new Validate;