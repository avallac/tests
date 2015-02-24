function getXmlHttp(){
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function vote() {
    var req = getXmlHttp()
    var statusElem = document.getElementById('result')
    var url=document.getElementById('url').value;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                statusElem.innerHTML = req.responseText;
            }else{
		statusElem.innerHTML = 'Please try again later.';
	    }
        }
    }
    req.open('POST', 'ajax/add', true);
    req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    req.send('url='+url); 
    statusElem.innerHTML = '<img src="/images/design/loading2.gif">';
}

