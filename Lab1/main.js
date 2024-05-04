function drawPicter(picters,region)
{
    let canvas = document.getElementById(region);
    let ctx = canvas.getContext('2d');
    let img = new Image();
    img.src = picters;
    img.onload = function(){
        ctx.drawImage(img, 0, 0, 300, 150)
    }
    canvas.hidden = false;
}

function clearImage(region)
{
    let canvas = document.getElementById(region);
    let ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, 300, 150);
    canvas.hidden = true;
}

var amoutOfTeapots = 4;
function texnoON(){   
    for (var i = 1; i <= amoutOfTeapots; i++)
    drawPicter("tehno/tehno_" + i + ".jpg", "teapotshow" + i);

    document.getElementById('texnoSwitch').value = 'Скрыть технику';
    document.getElementById('textTexno').innerHTML = 'Есть';
}

function texnoOFF(){
    for (var i = 1; i <= amoutOfTeapots; i++)
    clearImage( "teapotshow" + i);

    document.getElementById('texnoSwitch').value = 'Показать технику';
    document.getElementById('textTexno').innerHTML = "Нету";
}

function countTexno(value){
    if (document.getElementById('texnoSwitch').value == 'Скрыть технику'){
        texnoOFF();
        amoutOfTeapots = value;
        texnoON();
    }
    else{
        texnoOFF();
        amoutOfTeapots = value;
    }
}

function texnoSwith(){
    if (document.getElementById('texnoSwitch').value == 'Показать технику')
        texnoON();
    else
        texnoOFF();
}