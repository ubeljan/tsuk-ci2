function getletter(num) {
    if (num < 10) {
        return num;
    } else {
        if (num == 10) {
            return "A";
        }
        if (num == 11) {
            return "B";
        }
        if (num == 12) {
            return "C";
        }
        if (num == 13) {
            return "D";
        }
        if (num == 14) {
            return "E";
        }
        if (num == 15) {
            return "F";
        }
    }
}

function hexfromdec(num) {
    if (num > 65535) {
        return "err!";
    }
    first = Math.round(num / 4096 - 0.5);
    temp1 = num - first * 4096;
    second = Math.round(temp1 / 256 - 0.5);
    temp2 = temp1 - second * 256;
    third = Math.round(temp2 / 16 - 0.5);
    fourth = temp2 - third * 16;
    return "" + getletter(third) + getletter(fourth);
}

function URLEncode(text) {
    var i;
    var hex = "";
    for (i = 0; i < text.length; ++i) {
        hex += "%" + hexfromdec(text.charCodeAt(i));
    }
    return hex;
}

function URLDecode(encodedString) {
    var output = encodedString;
    var binVal, thisString;
    var myregexp = /(%[^%]{2})/;
    while ((match = myregexp.exec(output)) != null &&
        match.length > 1 && match[1] != "") {
        binVal = parseInt(match[1].substr(1), 16);
        thisString = String.fromCharCode(binVal);
        output = output.replace(match[1], thisString);
    }
    return output;
}

function ajax(page, box, parameters, method, loadmessage, call, callparas) {
    ajax_responsetext = "";
    var silent = false;
    if (box == undefined || box == "") {
        silent = true;
    }
    if (method == undefined || method == "") {
        method = "GET";
    }
    if (callparas == undefined) {
        callparas = "";
    }
    if (loadmessage == undefined) {
        loadmessage = "";
    }
    function new_request() {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest;
        } else if (window.ActiveXObject) {
            try {
                return new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    return new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                }
            }
        }
    }
    var xmlhttp = new_request();
	var page = escape(page);
    if (page !== "") {
        if (method == "POST") {
            var url = page;
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            xmlhttp.send(parameters);
        } else {
            if (parameters == "") {
                var url = page;
            } else {
                var url = page + ("?" + parameters + "&trans=" + Math.random());
            }
            xmlhttp.open("GET", url, true);
            xmlhttp.setRequestHeader("Content-Type", "text/html; Charset=ISO_8859-1");
            xmlhttp.send(null);
        }
        if (loadmessage != "") {
            document.getElementById(box).innerHTML = loadmessage;
        }
        xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 || xmlhttp.readyState == "complete") {
				if (xmlhttp.status == 200) {
					if (silent == false) {
						document.getElementById(box).innerHTML = xmlhttp.responseText;
					}
					ajax_responsetext = xmlhttp.responseText;
					if (call != "" && call != undefined) {
						call(callparas);
					}
				} else {
					if (silent == false) {
						document.getElementById(box).innerHTML = "Error Fetching Page";
					}
				}
			}
		};
    } else if (page == "") {
        if (silent == false) {
            document.getElementById(box).innerHTML = "Error: No Page Specified";
        }
    }
}