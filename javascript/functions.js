function nl2br(str){
    return (str + '').replace(/([^>])\n/g, '<br>\n');
}

function br2nl(str){
    return str.replace(/<br\s*\/?>/mg,"\n");
}