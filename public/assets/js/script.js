
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