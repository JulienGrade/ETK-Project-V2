let balls = document.getElementsByClassName("ball");
document.onmousemove = function eyes(){
    // console.log('salut');
    var x = event.clientX * 100 / window.innerWidth + "%";
    var y = event.clientY * 100 / window.innerHeight + "%";

    for(var i=0;i<2;i++){
        balls[i].style.left = x;
        balls[i].style.top = y;
        balls[i].style.transform = "translate(-"+x+", -"+y+")";
    }
};

// let balls = document.getElementsByClassName("ball");
// let fantom = document.getElementsByClassName("fantom");
// document.onmousemove = function ghost(){
//     // console.log('salut');
//     var x = event.clientX * 100 / window.innerWidth + "%";
//     var y = event.clientY * 100 / window.innerHeight + "%";
//
//     for(var i=0;i<2;i++){
//         balls[i].style.left = x;
//         balls[i].style.top = y;
//         balls[i].style.transform = "translate(-"+x+", -"+y+")";
//         fantom[i].style.left = x;
//         fantom[i].style.top = y;
//         fantom[i].style.transform = "translate(-"+x+", -"+y+")";
//
//     }
// };

document.getElementById("fantom").style.opacity = "1";
function onFocus() {
    console.log('onfocus');
    // document.getElementById("eyes").style.opacity = "0";
    document.getElementById("eyes").classList.add('fadeout');
    document.getElementById("eyes").classList.remove('fadein');
}
function onBlur() {
    console.log('onblur');
    // document.getElementById("eyes").style.opacity = "1";
    document.getElementById("eyes").classList.add('fadein');
    document.getElementById("eyes").classList.remove('fadeout');
}

