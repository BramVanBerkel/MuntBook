import { SVGGraph } from "calendar-graph";
import chroma from "chroma-js";

async function getCalendarData() {
    const request = await fetch(`/api/addresses/${address}/mining-address-calendar`);
    return await request.json();
}

getCalendarData().then(data => {
    const max = Math.max(...data.map(item => item.count));

    const scale = chroma.scale(['#a3d6ff', '#000e66']);

    new SVGGraph("#calendar", data, {
        size: 18,
        colorFun: (block) => (block.count === 0) ? '#eeeeee' : scale(block.count / max).hex()
    });

    const tip = document.getElementById("calendar-tooltip");
    let elems = document.getElementsByClassName("cg-day");

    const mouseOver = function(e) {
        const rect = e.target.getBoundingClientRect();
        let count = parseInt(e.target.getAttribute("data-count"));
        count = (count === 0) ? 'no' : count;
        const date = e.target.getAttribute("data-date");
        console.log(e)
        tip.style.display = "block";
        tip.textContent = `${count} rewards found on ${date}`;
        const w = tip.getBoundingClientRect().width;
        tip.style.left = `${rect.left - (w / 2) + 6}px`;
        tip.style.top = `${rect.top - 35}px`;
    };
    const mouseOut = function(e) {
        e = e || window.event;
        tip.style.display = "none";
    };
    for (let i = 0; i < elems.length; i++) {
        if (document.body.addEventListener) {
            elems[i].addEventListener("mouseover", mouseOver, false);
            elems[i].addEventListener("mouseout", mouseOut, false);
        } else {
            elems[i].attachEvent("onmouseover", mouseOver);
            elems[i].attachEvent("onmouseout", mouseOut);
        }
    }
})

