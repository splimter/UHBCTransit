let set_waypoint = document.getElementById('set_waypoint')
let set_path = document.getElementById('set_path')
let btn_close = document.getElementById('btn_close')
let txt_desc = document.getElementById('txt_desc')
let txt_start = document.getElementById('txt_start')
let txt_end = document.getElementById('txt_end')
let btn_submit = document.getElementById('btn_submit')
let sideOptions = document.getElementById('sideOptions')
let exampleModalLabel = document.getElementById("exampleModalLabel")
let selectedId

if (set_waypoint)
    set_waypoint.addEventListener('click', e => {
        mode = 'waypoint'
        chk = 0
        btn_submit.innerHTML = "Add"
        exampleModalLabel.innerHTML = "Add a Pin"
        txt_desc.value = ""
        txt_start.value = ""
        txt_end.value = ""
        $('#exampleModal').modal('toggle')
    })
if (set_path)
    set_path.addEventListener('click', e => {
        mode = 'path'
        chk = 0
    })
btn_close.addEventListener('click', e => {
    mode = ""
})
txt_desc.addEventListener('input', e => {
    btn_submit.disabled = txt_desc.value === "";
})
btn_submit.addEventListener('click', e => {
    if (btn_submit.innerHTML === "Add") {
        desc = txt_desc.value;
        start = txt_start.value;
        end = txt_end.value;
    }
    if (btn_submit.innerHTML === "Update") {
        var mark = null
        // update desc in objPins
        objPins.map(e => {
            mark = L.marker([e.lati, e.long])
            if (e.id === selectedId) {
                e.desc = txt_desc.value
            }
        })
        // update desc in db
        updatePinsInDB(selectedId, e.lati, e.long, txt_desc.value)

        // update desc in bindPopup
        mark.bindPopup(txt_desc.value)

        // update desc in aside
        const elem = document.getElementById("span" + selectedId);
        if (isAuth)
            elem.innerHTML = `<span class="material-icons">place</span>${txt_desc.value}
            <span id="span_e${selectedId}" class="material-icons">edit</span>
            <span id="span_rc${selectedId}" class="material-icons">remove_circle_outline</span>`
        else
            elem.innerHTML = `<span class="material-icons">place</span>${txt_desc.value}`

        // remove old then replace it
        // remove old
        marks.map(e => {
            if (e.id == selectedId)
                mymap.removeLayer(e.mark)
        })
        // add new one
        L.layerGroup([mark]).addTo(mymap);
    }
})

addEventListener("click", function (evnt) {
    let brutId = evnt.target.id

    console.log(evnt.target.id)
    if (brutId.indexOf("span_e") === 0) {
        chk = 0
        objPins.map(e => {
            // change modal appearance and lunch it
            if (e.id == brutId.slice(6)) {
                $("#txt_desc").val(e.desc)
                exampleModalLabel.innerHTML = "Update Pin"
                btn_submit.innerHTML = "Update"
                selectedId = e.id
                $('#exampleModal').modal('toggle')
            }
        })
    } else if (brutId.indexOf("span_rc") === 0) {
        // remove mark from map
        marks.map(e => {
            if (e.id == brutId.slice(7))
                mymap.removeLayer(e.mark)
        })
        // remove mark from aside
        const elem = document.querySelector('#span' + brutId.slice(7));
        elem.parentNode.removeChild(elem);
        // remove mark from db
        deletePin(brutId.slice(7))
    } else if (brutId.indexOf("r") === 0) {
        // remove path from map
        polylines.map(e => {
            if (e.id == brutId.slice(1))
                mymap.removeLayer(e.layer)
        })
        // remove path from db
        deletePath(brutId.slice(1))
    } else if (brutId.indexOf("e") === 0)
        // set view to end path
        objPaths.map(e => {
            if (e.id == brutId.slice(1))
                mymap.setView([e.end[0], e.end[1]], 16.25);
        })
    else if (brutId.indexOf("s") === 0)
        // set view to start path
        objPaths.map(e => {
            if (e.id == brutId.slice(1))
                mymap.setView([e.start[0], e.start[1]], 16.25);
        })


})

//  data-toggle="modal" data-target="#exampleModal"
