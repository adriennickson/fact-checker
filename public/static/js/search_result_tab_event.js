let buttons = document.querySelectorAll("#search-result-nav button");
console.log(buttons)
buttons.forEach(button => button.onclick = e => {

    oldSelected = document.querySelector("#search-result-nav > .border-b-2")
    "text-blue-500 border-b-2 font-medium border-blue-500".split(" ").forEach(i => {
        oldSelected.classList.remove(i)
    })
    "text-blue-500 border-b-2 font-medium border-blue-500".split(" ").forEach(i => {
        if (e.target.dataset.target)
            e.target.classList.add(i);
        else
            e.target.parentNode.classList.add(i);
    })

    document.querySelectorAll(".search-results:not(.hidden)").forEach((el) => el.classList.add("hidden"));
    if (e.target.dataset.target)
        document.querySelector("#" + e.target.dataset.target).classList.remove("hidden");
    else
        document.querySelector("#factchecker-result").classList.remove("hidden");

})