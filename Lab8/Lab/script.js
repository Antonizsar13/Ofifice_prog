function createHiddenCode(number){
    const inputElement = document.createElement('input');
    inputElement.type = 'hidden';
    inputElement.value = number;
    inputElement.name = 'numberOtdel';
    
    const tableElement = document.getElementById('table');
    tableElement.appendChild(inputElement);
}


function sizeInfo(){

        var iframe = document.getElementById('myIframe');
        var div = document.getElementById('under');
        
        iframe.onload = function() {
            var iframeHeight = iframe.contentWindow.document.body.scrollHeight + 50 + 'px';
            div.style.height = iframeHeight;
            var iframeWidth = iframe.contentWindow.document.body.scrollWidth + 10 + 'px';
            div.style.width = iframeWidth;
        };  
}

let countClick = 0;
document.addEventListener('click', function(event) {

        var myDiv = document.getElementById('under');
        var targetElement = event.target;
        
        console.log(targetElement);
        console.log(myDiv);
         if (targetElement !== myDiv && !myDiv.contains(targetElement) && myDiv.style.display == 'block') {
            countClick += 1;

            if (countClick > 1){
                countClick = 0;
                myDiv.style.display = 'none';
                const bodyElement = document.getElementById('div_blur');
                bodyElement.style.filter = 'blur(0px)';

            }
        }
    });

function viewDiagram(otdel){
    var div = document.getElementById('under');
    div. innerHTML = '';
    div.style.display = 'block';

    var iframe = document.createElement('iframe');
    iframe.id = 'myIframe';
    iframe.src = 'viewDiagram.php?otdel=' + otdel;
    iframe.width = '100%';
    iframe.height = '100%';

    const bodyElement = document.getElementById('div_blur');
    bodyElement.style.filter = 'blur(5px)';

    div.appendChild(iframe);
    sizeInfo();
}