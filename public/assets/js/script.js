
function prev(){
    document.getElementById('slider-container').scrollLeft -= 270;
}

function next()
{
    document.getElementById('slider-container').scrollLeft += 270;
}
function myFunction() {
    let heros = document.getElementsByClassName('hero')
    for(let hero of heros) {
    if (hero.disabled){
            hero.disabled = false;
            } else if (!hero.disabled){
            hero.disabled = true;
            }
    }
}
let imageArticleDetails = document.getElementsByClassName('imageArticleDetails')

// function showSearchEventFormType(){
//     let searchEventFormType = document.getElementById('searchEventFormType')
//     if(getComputedStyle(searchEventFormType).display != "none"){
//         searchEventFormType.style.display = "none";
//       } else {
//         searchEventFormType.style.display = "block";
//     }
//     for (let la of imageArticleDetails) {
//         if(getComputedStyle(la).height != 500+"px"){
//             la.style.height = 500+"px";
//           } else {
//             la.style.height = 350+"px";
//         }
//     }
// }
function showSearchEventFormType(){
    for (let la of imageArticleDetails) {
        if(getComputedStyle(la).height != 500+"px"){
            la.style.height = 500+"px";
          } else {
            la.style.height = 350+"px";
        }
    }
}