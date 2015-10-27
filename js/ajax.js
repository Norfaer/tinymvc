function ajaxQuery(myurl,params,func)
{
    var rurl=myurl+'?rand='+Math.random();
    $.ajax({
        cache:false,
        type:"POST",
        url:rurl,
        data:params,
        success:func,
        dataType:'json'
    });
}

function validInput(target)
{
    var vtype="any",param1="1",param2="",str,m,re =  /^([a-z]*)(?:[-_](\d{1,})[-_](\d{1,}))?$/;
    str = target.attr("class");
    if (!str) return true;
    if ((m = re.exec(str)) !== null) {
        vtype=m[1];
        if (m[0] !== m[1]){ param1 = m[2]; param2 = m[3]; }
        switch (vtype) {
            case "login": re = /^([a-zA-Z0-9_]{5,16})$/; break;
            case "password": re = /^([a-zA-Z0-9\\\/,.:;`~'"_!?+=@%^&()#$|-]{5,16})$/; break;
            case "email": re = /([a-zA-Z0-9._-]+@+[a-zA-Z][a-zA-Z0-9._-]+.+[a-z])/; break;
            case "float": re = /^[+-]?(\d{1,}[\.,]\d{1,}|\d*)$/; break;
            case "int": re = new RegExp("^[+-]?\\d{" + param1 + "," + param2 + "}$"); break;
            case "any": re = new RegExp("^.{" + param1 + "," + param2 + "}$"); break;    
        }
        return (re.exec(target.val()) !== null);
    }
    else
        return false;
}